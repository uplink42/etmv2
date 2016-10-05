<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{
    private $email;
    private $password;
    private $reports;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "Settings";
        $this->load->model('Settings_model');

        if (!empty($_REQUEST['password']) && !empty($_REQUEST['email'])) {
            $this->email    = $_REQUEST['email'];
            $this->password = $_REQUEST['password'];
        }

        if(!empty($_REQUEST['reports'])) {
            $this->reports = $_REQUEST['reports'];
        }
    }

    public function index($character_id)
    {

        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "settings";

            $data['view'] = 'main/settings_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function email()
    {
        $data = $this->Settings_model->getEmail($this->session->iduser);

        echo json_encode(array("email" => $data));
    }

    public function reports()
    {
        $result = $this->Settings_model->getReportSelection($this->session->iduser);
        $data = array("data" => $result);

        echo json_encode($data);
    }

    public function changeEmail()
    {
        //check pw
        $this->load->model('Login_model');
        if ($this->Login_model->validate($this->session->username, $this->password, true)) {
            if($this->Settings_model->changeEmail($this->session->iduser, $this->email)) {
                $notice = "success";
                $message = Msg::EMAIL_CHANGE_SUCCESS;
            } else {
                $notice = "error";
                $message = Msg::DB_ERROR;
            }
        } else {
            $notice = "error";
            $message = Msg::INVALID_LOGIN;
        }

        echo json_encode(array("notice" => $notice, "message" => $message));

    }

    public function changeReports()
    {
        if($this->reports == 'none' || $this->reports == 'daily' || $this->reports == 'weekly' || $this->reports == 'monthly') {
            if($this->Settings_model->changeReports($this->session->iduser, $this->reports)) {
                $notice = "success";
                $message = Msg::REPORT_CHANGE_SUCCESS;
            } else {
                $notice = "error";
                $message = Msg::REPORT_CHANGE_ERROR;
            }
        } else {
            $notice = "error";
            $message = Msg::REPORT_CHANGE_ERROR;
        }

        $data = ["notice" => $notice, "message" => $message];
        echo json_encode($data);
        
    }
}
