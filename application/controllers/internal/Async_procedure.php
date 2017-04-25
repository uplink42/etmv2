<?php
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Pheal\Core\Config;
use Pheal\Pheal;

class Async_procedure extends CI_Controller
{

	public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->model('internal/ReportGenerator', 'reports');
        $this->load->model('Updater_model');
        $this->load->model('common/User');
        $this->load->model('Login_model');
        $this->load->model('common/ValidateRequest');
        $this->load->model('common/Email');
    }

    public function index($iduser)
    {
        if (!$this->input->is_cli_request()) {
            die('unauthorized');
        }
        
    	$this->db->where('iduser', $iduser);
        $username = $this->db->get('user')->row()->username;

    	//$this->load->model('Updater_model');
    	$this->Updater_model->init($username);
    	try {
    		$result_iterate = $this->Updater_model->iterateAccountCharacters();
    	} catch (Throwable $e) {
    		log_message('error', $username . ' GLOBAL ERROR ->' . $e->getMessage());
    	}
        if (!$result_iterate) {
            log_message('error', $username . ' GLOBAL ERROR ->' . Msg::DB_ERROR);
        } else {
        	try {
                $this->load->model('Updater_profit_model', 'profits');
                $this->profits->beginProfitCalculation($username);
                // update totals and history
                $this->Updater_model->updateTotals($username, true);
		        // send email
                $this->db->where('iduser', $iduser);
                $query = $this->db->get('user');
                $report_type = $query->row()->reports;

                $this->sendReport($report_type, $username);

	            $this->Updater_model->release($username);
        	} catch (Throwable $e) {
        		log_message('error', $username . ' GLOBAL ERROR ->' . $e->getMessage());
        	}
        }
    	$this->Updater_model->release($username);
    }

    public function sendReport(string $period, string $username)
    {
        $period == 'daily' ? $interval = 1 : 
            ($period == 'weekly' ? $interval = 7 : 
                ($period == 'monthly' ? $interval = 30 : 'none'));

        // if today is sunday send weekly report
        // if today is the day of the month send monthly report
        $day = date('D');
        $date = date('d');
        $month = date('m');

        switch ($period) {
            case 'none';
                return;
            break;

            case 'weekly':
                if ($day != 'Mon') {
                    return;
                }
            break;

            case 'monthly':
                if (!($day == 'Mon' && $date == '30') || !($day == 'Mon' && $date == '28' && $month == 'February')) {
                    return;
                }
            break;    
        }
        
        $data['period']    = $period;
        $data['interval']  = $interval;
        $data['recap_int'] = max($interval, 7);
        $this->db->where('username', $username);

        $data['user_id']     = (int) $this->db->get('user')->row()->iduser;
        $characters          = $this->Login_model->getCharacterList($data['user_id']);
        $chars               = (string) $characters['aggr'];
        $data['char_names']  = $characters['char_names'];

        if($chars != '()') {
            $data['totals'] = $this->reports->calculateTotals($chars, $interval);
            if ($data['totals'][1][0]['total_profit'] == 0 && $data['totals'][1][0]['total_sell'] == 0 && $data['totals'][1][0]['total_buy'] == 0) {
                return;
            }
            
            // using the icons helper
            $data['best_raw']       = injectIcons($this->reports->calculateBestRaw($chars, $interval));
            $data['best_margin']    = injectIcons($this->reports->calculateBestMargin($chars, $interval));
            $data['problematic']    = injectIcons($this->reports->calculateProblematicItems($chars, $interval));
            $data['best_customers'] = $this->reports->calculateBestCustomers($chars, $interval);
            $data['fastest']        = injectIcons($this->reports->calculateFastestTurnovers($chars, $interval));
            $data['best_iph']       = injectIcons($this->reports->calculateBestIPH($chars, $interval));
            //$data['blunders']       = $this->injectIcons($this->reports->calculateBlunders($chars, $interval));
            $data['best_stations']  = $this->reports->calculateBestStations($chars, $interval);
            $data['recap']          = $this->reports->calculateRecap($chars, $data['recap_int']);
            $data['username']       = $username;
            $data['date_now']       = date('Y-m-d');
            $data['date_prev']      = date('Y-m-d', strtotime('-' . $interval * 24 . ' hours'));
            $data['cl_recent']      = $this->Updater_model->getChangeLog(true);

            $report = $this->load->view('reports/reports_v', $data, true);

            //mail data
            $address = $this->User->getUserEmail($data['user_id']);
            $from = "etmdevelopment42@gmail.com";
            $from_name = "Eve Trade Master";
            $subject = "Eve Trade Master " . $period . " earnings report for " . $data['date_now'];
            $body = $report;

            $mail = $this->Email->send($address, $from, $from_name, $subject, $body);
        }
    }
}