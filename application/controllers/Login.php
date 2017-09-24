<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('etmsession');
        $this->load->library('twig');
        $this->load->helper('msg');
    }

    public function index()
    {
        $data['no_header'] = 1;
        $data['SESSION']   = $_SESSION;
        $data['view']      = 'login/login_v';
        $this->twig->display('main/_template_v', $data);
    }

    /**
     * Attempts to login a user
     * @return void
     */
    public function process()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        if (empty($username) || empty($password)) {
            $this->loginFail();
            return;
        }

        $this->load->model('User_model', 'user');
        $user = $this->user->getOne(['username' => $username]);
        if (!$user) {
            $this->loginFail();
            return;
        }

        // validate hash
        $salt            = $user->salt;
        $password_salted = crypt($password, $salt);
        $login           = $this->user->getOne(['username' => $username, 'password' => $password_salted]);
        if (!$login) {
            $this->loginFail();
            return;
        }

        // set session variables
        $session = array(
            'iduser'   => $login->iduser,
            'username' => $login->username,
            'password' => $login->password,
            'email'    => $login->email,
        );
        if(!$nosession) {
            $this->etmsession->setData($session);
        }
        redirect(base_url('updater'));
    }

    private function loginFail()
    {
        buildMessage("error", Msg::INVALID_LOGIN);
        $this->index();
    }
}
