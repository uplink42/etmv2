<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Register extends CI_Controller
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
        $data['username']       = $this->input->post('username', true);
        $data['password']       = $this->input->post('password', true);
        $data['repeatpassword'] = $this->input->post('repeatpassword', true);
        $data['email']          = $this->input->post('email', true);
        $data['token']          = $this->input->post('token', true);
        $data['refresh']        = $this->input->post('refresh', true);
        $data['character']      = $this->input->post('character', true);
        $data['name']           = $this->input->post('name', true);
        $data['reports']        = $this->input->post('reports');

        $this->load->model('Register_model', 'register');
        $result = $this->register->validate($data['username'], $data['password'], $data['repeatpassword'], 
            $data['email'], $data['reports'], $data['character']);

        if (!isset($result['username']) &&
            !isset($result['password']) &&
            !isset($result['email']) &&
            !isset($result['token']) &&
            !isset($result['character']) &&
            !isset($result['refresh'])) {

            $data['view']       = "register/register_settings_v";
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
    public function processSettings(): void
    {
        $this->load->model('register_model', 'register');
        $user_data = [
            'username'         => $this->input->post('username', true),
            'password'         => $this->input->post('password', true),
            'email'            => $this->input->post('email', true),
            'token'            => $this->input->post('token', true),
            'refresh'          => $this->input->post('refresh', true),
            'character'        => $this->input->post('character', true),
            'reports'          => $this->input->post('reports'),
            'default_buy'      => $this->input->post('default-buy', true),
            'default_sell'     => $this->input->post('default-sell', true),
            'x_character'      => $this->input->post('x-character', true),
            'null_citadel_tax' => $this->input->post('null-citadel-tax', true),
            'null_station_tax' => $this->input->post('null-station-tax', true),
            'null_outpost_tax' => $this->input->post('null-outpost-tax', true),
            'null_buy_tax'     => $this->input->post('null-buy-tax', true),
            'null_sell_tax'    => $this->input->post('null-sell-tax', true),
        ];
        print_r($user_data);
        $result = $this->register->createAccount($user_data);
        if (!$result['success']) {
            $data['message']    = $result['msg'];
            $data['notice']     = "error";
            $data['characters'] = $this->register->getCharacters($user_data['apikey'], $user_data['vcode']);
            $data['view']       = "register/register_settings_v";
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
