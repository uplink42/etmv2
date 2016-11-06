<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->load->model('common/Msg');
    }

    public function index(string $view = null)
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
        $this->load->model('common/Auth');
        if ($this->Auth->validateLogin($username, $password)) {
            //login success
            $user_data = $this->Login_model->getUserData($username);
            $id_user   = $user_data->iduser;
            $email     = $user_data->email;

            $session_start = date('Y-m-d H:i:s');
            $session_data = array("username" => $username,
                "start"                          => $session_start,
                "iduser"                         => $id_user,
                "email"                          => $email
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
}
