<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Logout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('etmsession');
        $this->load->library('twig');
        $this->load->model('common/Msg');
    }

    /**
     * Terminates a user session
     * @return bool
     */
    public function index(): bool
    {
        $data['view'] = "login/login_v";
        buildMessage("success", Msg::LOGOUT, $data['view']);
        $data['no_header'] = 1;

        $this->etmsession->delete('username');
        $this->etmsession->delete('start');
        $this->etmsession->delete('iduser');

        $this->twig->display('main/_template_v', $data);
        return false;
    }
}
