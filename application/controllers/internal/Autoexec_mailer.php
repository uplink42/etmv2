<?php declare (strict_types = 1);
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

class Autoexec_mailer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('internal/ReportGenerator', 'reports');
        $this->load->model('Login_model');
    }

    //daily, weekly, monthly
    public function index(string $period = "daily")
    {
        $this->load->model('common/User');
        $users = $this->User->getUsersByReports($period);

        $period == 'daily' ? $interval = 1 : 
            ($period == 'weekly' ? $interval = 7 : 
                ($period == 'monthly' ? $interval = 30 : ''));

        foreach ($users as $row) {
            $data = [];
            $user_id     = (int) $row->iduser;
            $characters  = $this->Login_model->getCharacterList($user_id);
            $chars       = (string) $characters['aggr'];
            $data['char_names']  = $characters['char_names'];

            if($chars != '()') {
                $data['totals']         = $this->reports->calculateTotals($chars, $interval);
                $data['best_raw']       = $this->reports->calculateBestRaw($chars, $interval);
                $data['best_margin']    = $this->reports->calculateBestMargin($chars, $interval);
                $data['problematic']    = $this->reports->calculateProblematicItems($chars, $interval);
                $data['best_customers'] = $this->reports->calculateBestCustomers($chars, $interval);
                $data['fastest ']       = $this->reports->calculateFastestTurnovers($chars, $interval);
                $data['best_iph']       = $this->reports->calculateBestIPH($chars, $interval);
                $data['blunders']       = $this->reports->calculateBlunders($chars, $interval);
                $data['best_stations']  = $this->reports->calculateBestStations($chars, $interval);
                $data['recap']          = $this->reports->calculateRecap($chars, $interval);
            }

            $this->load->view('reports/reports_v', $data);
        }


    }

}
