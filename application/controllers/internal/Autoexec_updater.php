<?php //defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Pheal\Core\Config;
use Pheal\Pheal;

class Autoexec_updater extends MY_Controller
{
    private $iduser;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Log');
        $this->load->model('common/User');
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_updater_model');
        $this->load->model('Updater_model');
        $this->load->model('common/ValidateRequest');
        $this->load->model('internal/ReportGenerator', 'reports');
        $this->load->model('Login_model');
        $this->load->model('common/Email');
    }

    /**
     * Kickstarts the daily update procedure for every user
     * @return [type] [description]
     */
    public function index()
    {
    	if (!$this->input->is_cli_request()) {
            die();
        }
        
        try {
           $chars = $this->Autoexec_updater_model->getAllUsers(); 
       } catch (Throwable $e) {
            echo sprintf(
                "an exception was caught! Type: %s Message: %s",
                get_class($e),
                $e->getMessage()
            );
       }
        
        foreach ($chars as $row) {
            $start    = microtime(true);
            $username = $row->username;
            $this->iduser = (int) $row->iduser;
            echo $username . "->" . $this->iduser . "\n";

            try {
                $this->Updater_model->init($username);
            } catch (Throwable $e) {
                $this->Updater_model->release($username);
                log_message('error', $e->getMessage());
                echo sprintf(
                    "an exception was caught! Type: %s Message: %s",
                    get_class($e),
                    $e->getMessage()
                );
            }

            // check if API server is up
            if (!$this->ValidateRequest->testEndpoint()) {
                echo XML_CONNECT_FAILURE;
            } else {
                if ($this->Updater_model->isLocked($username)) {
                    echo "User already updating. Aborting..." . "\n";
                } else {
                    //check if user has any keys
                    $keys = $this->Updater_model->getKeys($username);
                    if (count($keys) == 0) {
                        echo "No keys" . "\n";
                    } else {
                        //validate existing keys
                        if (!$this->Updater_model->processAPIKeys($keys, $username)) {
                            echo "No valid keys" . "\n";
                        } else {
                            //begin update
                            $this->Updater_model->lock($username);
                            try {
                                $result_iterate = $this->Updater_model->iterateAccountCharacters();
                                if (!$result_iterate) {
                                    echo Msg::DB_ERROR . "\n";
                                } else {
                                	//if we arrived here, that means nothing went wrong (yet)
							        //calculate profits
                                    try {
                                        $this->db->trans_start();
                                        $this->Updater_model->calculateProfits();
                                        //totals and history
                                        $this->Updater_model->updateTotals();
                                        $this->db->trans_complete();
                                    } catch (Throwable $e) {
                                        $this->Updater_model->release($username);
                                        log_message('error', $e->getMessage());
                                        echo sprintf(
                                            "an exception was caught! Type: %s Message: %s",
                                            get_class($e),
                                            $e->getMessage()
                                        );
                                    }

                                    // get daily sum
                                    $this->Updater_model->updateTotals(true, $username);
                                    
                                    // send email
                                    $this->db->where('username', $username);
                                    $report_type = $this->db->get('user')->row()->reports;

                                    $this->sendReport($report_type);
                                    $this->Updater_model->release($username);

                                    $finish = microtime(true);
                                    echo number_format($finish - $start, 2) . " for username " . $username . "\n";
                                }
                            } catch (Throwable $e) {
                                //if an exception happens during update (this is a bug on Eve's API)
                                echo sprintf(
                                    "an exception was caught! Type: %s Message: %s",
                                    get_class($e),
                                    $e->getMessage()
                                );
                                $this->Updater_model->release($username);

                                //cache is now corrupted for 24 hours, remove cache and try again
                                //remove all keys just in case
                                //$problematicKeys = $this->Updater_model->getAPIKeys($this->user_id);
                                foreach ($keys as $row) {
                                	echo 'deleting cache. canceled update ' . $this->iduser;
                                    $key = $row['apikey'];

                                    $dir = FILESTORAGE . $key;
                                    $this->removeDirectory($dir);
                                    $this->Log->addEntry('clear', $this->iduser);
                                    //release the lock
                                }
                            }
                        }
                    }
                }
            }
            // make sure lock is released in case of exceptions, timeouts, ...
            $this->Updater_model->release($username);
        }
    }

    /**
     * Removes a directory when cache files are corrupt
     * @param  [type] $path 
     * @return void       
     */
    private function removeDirectory($path) : void
    {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                is_dir($file) ? $this->removeDirectory($file) : unlink($file);
            }
            rmdir($path);
        }
    }


    public function sendReport(string $period, string $username)
    {
        $period == 'daily' ? $interval = 1 : 
            ($period == 'weekly' ? $interval = 7 : 
                ($period == 'monthly' ? $interval = 30 : ''));

        $data['period']    = $period;
        $data['interval']  = $interval;
        $data['recap_int'] = max($interval, 7);

        sleep(5);
        $data['user_id']     = (int) $this->iduser;
        $characters          = $this->Login_model->getCharacterList($data['user_id']);
        $chars               = (string) $characters['aggr'];
        $data['char_names']  = $characters['char_names'];

        if($chars != '()') {
            $data['totals']         = $this->reports->calculateTotals($chars, $interval);
            if ($data['totals'][1][0]['total_profit'] == 0 && $data['totals'][1][0]['total_sell'] == 0 && $data['totals'][1][0]['total_buy'] == 0) {
                echo 'No data for this user' . "\n";
                return;
            }
            
            $data['best_raw']       = $this->injectIcons($this->reports->calculateBestRaw($chars, $interval));
            $data['best_margin']    = $this->injectIcons($this->reports->calculateBestMargin($chars, $interval));
            $data['problematic']    = $this->injectIcons($this->reports->calculateProblematicItems($chars, $interval));
            $data['best_customers'] = $this->reports->calculateBestCustomers($chars, $interval);
            $data['fastest']        = $this->injectIcons($this->reports->calculateFastestTurnovers($chars, $interval));
            $data['best_iph']       = $this->injectIcons($this->reports->calculateBestIPH($chars, $interval));
            $data['blunders']       = $this->injectIcons($this->reports->calculateBlunders($chars, $interval));
            $data['best_stations']  = $this->reports->calculateBestStations($chars, $interval);
            $data['recap']          = $this->reports->calculateRecap($chars, $data['recap_int']);
            $data['username']       = $username;
            $data['date_now']       = date('Y-m-d');
            $data['date_prev']      = date('Y-m-d', strtotime('-24 hours'));
            $data['cl_recent']      = $this->Updater_model->getChangeLog(true);

            $report = $this->load->view('reports/reports_v', $data, true);

            //mail data
            $address = $this->User->getUserEmail($data['user_id']);;
            $from = "etmdevelopment42@gmail.com";
            $from_name = "Eve Trade Master";
            $subject = "Eve Trade Master " . $period . " earnings report for " . $data['date_now'];
            $body = $report;

            $mail = $this->Email->send($address, $from, $from_name, $subject, $body);

            if($mail) {
                echo "Message sent to " . $address . "\n";
            } else {
                echo "Error sending mail to " . $address . "\n";
            }
        }
    }
}
