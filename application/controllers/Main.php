<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    //loads the template view with the required content
    public function index($view = null)
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->load->view('main/_template_v', $data);
        //$this->load->view('main/header_v', $data);
    }

    public function login()
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->load->view('main/_template_v', $data);
    }

    public function register()
    {
        $data['no_header'] = 1;
        $data['view']      = 'register/register_v';
        $this->load->view('main/_template_v', $data);
    }

    public function headerData($character_id, $aggr = 0)
    {
        $this->load->model('Nav_model');
        if ($this->Nav_model->checkCharacterBelong($character_id, $this->session->iduser)) {
            if($aggr == 0) {
                $result = json_encode($this->Nav_model->getHeaderData($character_id));
                echo $result;
            } else {
                $this->load->model('Login_model');
                $characters = $this->Login_model->getCharacterList($this->session->iduser);
                $chars = $characters['aggr'];
                $result = json_encode($this->Nav_model->getHeaderData($character_id, $chars));
                echo $result;
            }
            
        }
    }

}
