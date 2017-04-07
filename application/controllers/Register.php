<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('etmsession');
        $this->load->library('twig');
    }

    /**
     * Begins the user registration procedure
     * @return void
     */
    public function processData(): void
    {
        $username       = $this->input->post('username', true);
        $password       = $this->input->post('password', true);
        $repeatpassword = $this->input->post('repeatpassword', true);
        $email          = $this->input->post('email', true);
        $apikey         = (int) $this->input->post('apikey', true);
        $vcode          = $this->input->post('vcode', true);
        $reports        = $this->input->post('reports', true);

        $this->load->model('Register_model', 'register');
        $result = $this->register->validate($username, $password, $repeatpassword, $email, $apikey, $vcode, $reports);
        log_message('error', print_r($result, 1));

        if (!isset($result['username']) &&
            !isset($result['password']) &&
            !isset($result['email']) &&
            !isset($result['api']) &&
            !isset($result['reports'])) {
            $result = $this->register->getCharacters($apikey, $vcode);

            $data['characters'] = $result;
            $data['view']       = "register/register_characters_v";
            $data['apikey']     = $apikey;
            $data['vcode']      = $vcode;
            $data['no_header']  = 1;
            $this->twig->display('main/_template_v', $data);
        } else {
            $data['result']    = $result;
            $data['view']      = "register/register_v";
            $data['no_header'] = 1;
            $this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Begin registration operations and validations - step 2
     * @return void
     */
    public function processCharacters(): void
    {
        $this->load->model('register_model', 'register');
        $user_data = [
            'username'         => $this->input->post('username', true),
            'password'         => $this->input->post('password', true),
            'email'            => $this->input->post('email', true),
            'apikey'           => (int) $this->input->post('apikey', true),
            'vcode'            => $this->input->post('vcode', true),
            'reports'          => $this->input->post('reports', true),
            'default_buy'      => $this->input->post('default-buy', true),
            'default_sell'     => $this->input->post('default-sell', true),
            'x_character'      => $this->input->post('x-character', true),
            'null_citadel_tax' => $this->input->post('null-citadel-tax', true),
            'null_station_tax' => $this->input->post('null-station-tax', true),
            'null_outpost_tax' => $this->input->post('null-outpost-tax', true),
        ];

        $chars = array();
        if ($char1 = $this->input->post('char1', true)) {
            array_push($chars, $char1);
        } else {
            $char1 = "";
        }

        if ($char2 = $this->input->post('char2', true)) {
            array_push($chars, $char2);
        } else {
            $char2 = "";
        }

        if ($char3 = $this->input->post('char3', true)) {
            array_push($chars, $char3);
        } else {
            $char3 = "";
        }

        $user_data['chars'] = $chars;

        // no characters selected
        if (count($chars) == 0) {
            $characters         = $this->register->getCharacters($user_data['apikey'], $user_data['vcode']);
            $data['characters'] = $characters;
            buildMessage("error", Msg::NO_CHARACTER_SELECTED, "register/register_characters_v");
            $data['view']      = "register/register_characters_v";
            $data['no_header'] = 1;
            $this->twig->display('main/_template_v', $data);
            return;
        }

        if ($this->register->verifyCharacters($user_data['chars'], $user_data['apikey'], $user_data['vcode'])) {
            $result = $this->register->createAccount($user_data);
            if (!$result['success']) {
                $data['message']    = $result['msg'];
                $data['notice']     = "error";
                $data['characters'] = $this->register->getCharacters($user_data['apikey'], $user_data['vcode']);
                $data['view']       = "register/register_characters_v";
                $data['no_header']  = 1;
                $this->twig->display('main/_template_v', $data);
            } else {
                buildMessage('success', Msg::ACCOUNT_CREATE_SUCCESS);
                $data['SESSION']   = $_SESSION;
                $data['view']      = "login/login_v";
                $data['no_header'] = 1;
                $this->twig->display('main/_template_v', $data);
            }
        }
    }
}
