<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Autoexec_mailer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('internal/ReportGenerator', 'reports');
        $this->load->model('Login_model');
        $this->load->model('Updater_model');
        $this->load->model('common/Email');
    }

    //daily, weekly, monthly
    public function index(string $period = "daily")
    {
        if (!$this->input->is_cli_request()) {
            die();
        }

        $this->load->model('common/User');
        $users = $this->User->getUsersByReports($period);

        $period == 'daily' ? $interval = 1 : 
            ($period == 'weekly' ? $interval = 7 : 
                ($period == 'monthly' ? $interval = 30 : ''));

        $data['period']    = $period;
        $data['interval']  = $interval;
        $data['recap_int'] = max($interval, 7);

        foreach ($users as $row) {

      		sleep(5);
            $data['user_id']     = (int) $row->iduser;
            $characters          = $this->Login_model->getCharacterList($data['user_id']);
            $chars               = (string) $characters['aggr'];
            $data['char_names']  = $characters['char_names'];

            if($chars != '()') {
                $data['totals']         = $this->reports->calculateTotals($chars, $interval);
                $data['best_raw']       = $this->injectIcons($this->reports->calculateBestRaw($chars, $interval));
                $data['best_margin']    = $this->injectIcons($this->reports->calculateBestMargin($chars, $interval));
                $data['problematic']    = $this->injectIcons($this->reports->calculateProblematicItems($chars, $interval));
                $data['best_customers'] = $this->reports->calculateBestCustomers($chars, $interval);
                $data['fastest']        = $this->injectIcons($this->reports->calculateFastestTurnovers($chars, $interval));
                $data['best_iph']       = $this->injectIcons($this->reports->calculateBestIPH($chars, $interval));
                $data['blunders']       = $this->injectIcons($this->reports->calculateBlunders($chars, $interval));
                $data['best_stations']  = $this->reports->calculateBestStations($chars, $interval);
                $data['recap']          = $this->reports->calculateRecap($chars, $data['recap_int']);

                $data['username']  = $this->User->getUsername($data['user_id']);
                $data['date_now']  = date('Y-m-d');
                $data['date_prev'] = date('Y-m-d', strtotime('-24 hours'));
                $data['cl_recent'] = $this->Updater_model->getChangeLog(true);

                $report = $this->load->view('reports/reports_v', $data, true);

                //mail data
                $address = $this->User->getUserEmail($data['user_id']);
                //$address = "etmdevelopment42@gmail.com";
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
}
