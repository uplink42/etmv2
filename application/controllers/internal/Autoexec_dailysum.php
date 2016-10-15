<?php defined('BASEPATH') or exit('No direct script access allowed');

ini_set('mysql.connect_timeout', 3000);
ini_set('default_socket_timeout', 3000);
ini_set('max_execution_time', 0);

class Autoexec_dailysum extends CI_Controller
{
    //update all totals, apis and character data
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('Updater_model');
        $this->load->model('common/User');
    }

    public function index()
    {
        $users = $this->User->getUsers();
        $dateToday = date('Y-m-d',strtotime("-1 days"));

        foreach($users as $row) {
            $username = $row->username;
            $this->Updater_model->updateTotals(true, $username);
            echo "Updated " . $username . " for " . $dateToday . "\n";
        }
    }

}
