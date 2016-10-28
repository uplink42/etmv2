<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->load->model('common/Msg');
    }

    public function index()
    {
        $data['view'] = "login/login_v";
        buildMessage("success", Msg::LOGOUT, $data['view']);
        $data['no_header'] = 1;

        $this->session->unset_userdata('username');
        $this->session->unset_userdata('start');
        $this->session->unset_userdata('iduser');
        $this->load->view('main/_template_v', $data);

        return false;
    }

}
