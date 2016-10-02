<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
    }

    public function index($view = null)
    {

    }

    public function process()
    {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        $data             = array();
        $data['username'] = $username;
        $data['password'] = $password;

        $this->load->model('Login_model');
        if ($this->Login_model->validate($username, $password)) {
            //login success
            $user_data = $this->Login_model->getUserData($username);
            $id_user   = $user_data->iduser;

            $session_start = date('Y-m-d H:i:s');
            $this->load->library('session');
            $session_data = array("username" => $username,
                "start"                          => $session_start,
                "iduser"                         => $id_user,
            );

            //set cookie
            //set_cookie($name[, $value = ''[, $expire = ''[, $domain = ''[, $path = '/'[, $prefix = ''[, $secure = FALSE[, $httponly = FALSE]]]]]]])
            $this->session->set_userdata($session_data);
            redirect(base_url('Updater'));
        } else {
            buildMessage("error", Msg::INVALID_LOGIN, 'login/login_v');
            $data['no_header'] = 1;
            $this->load->view('main/_template_v', $data);
        }
    }

    public function logout()
    {

    }

    public function recoverUsername()
    {

    }

    public function recoverPassword()
    {

    }

}
