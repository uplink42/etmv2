<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('mysql.connect_timeout', 3000);
ini_set('default_socket_timeout', 3000);
ini_set('max_execution_time', 0);

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
    public function index($interval)
    {
        $this->load->model('common/User');
        $users = $this->User->getUsersByReports($interval);

        foreach($users as $row) {
            $characters = $this->Login_model->getCharacterList($row->iduser);
            foreach($characters as $char) {
                $chars      = $characters['aggr'];
                $char_names = $characters['char_names'];
                $chars = "(" . $character_id . ")";
            }
            
        
        }
    }

}
