<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index($view = null)
    {

    }

    public function processData()
    {
        $username       = $this->security->xss_clean($this->input->post('username'));
        $password       = $this->security->xss_clean($this->input->post('password'));
        $repeatpassword = $this->security->xss_clean($this->input->post('repeatpassword'));
        $email          = $this->security->xss_clean($this->input->post('email'));
        $apikey         = $this->security->xss_clean($this->input->post('apikey'));
        $vcode          = $this->security->xss_clean($this->input->post('vcode'));
        $reports        = $this->security->xss_clean($this->input->post('reports'));

        $this->load->model('register_model');
        $result = $this->register_model->validate($username, $password, $repeatpassword, $email, $apikey, $vcode, $reports);

        if (!isset($result['username']) && !isset($result['password']) && !isset($result['email']) && !isset($result['api']) && !isset($result['reports'])) {
            $this->load->model('register_model');
            $result = $this->register_model->getCharacters($apikey, $vcode);

            $data['characters'] = $result;
            $data['view']       = "register/register_characters_v";
            $data['apikey']     = $apikey;
            $data['vcode']      = $vcode;
            $data['no_header']  = 1;
            $this->load->view('main/_template_v', $data);
        } else {
            $data['result']    = $result;
            $data['view']      = "register/register_v";
            $data['no_header'] = 1;
            $this->load->view('main/_template_v', $data);
        }

    }

    public function processCharacters()
    {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email    = $this->security->xss_clean($this->input->post('email'));
        $apikey   = $this->security->xss_clean($this->input->post('apikey'));
        $vcode    = $this->security->xss_clean($this->input->post('vcode'));
        $reports  = $this->security->xss_clean($this->input->post('reports'));

        $chars = array();

        if ($char1 = $this->security->xss_clean($this->input->post('char1'))) {
            array_push($chars, $char1);
        } else {
            $char1 = "";
        }

        if ($char2 = $this->security->xss_clean($this->input->post('char2'))) {
            array_push($chars, $char2);
        } else {
            $char2 = "";
        }

        if ($char3 = $this->security->xss_clean($this->input->post('char3'))) {
            array_push($chars, $char3);
        } else {
            $char3 = "";
        }

        if (count($chars) == 0) {
            $this->load->model('register_model');
            $characters         = $this->register_model->getCharacters($apikey, $vcode);
            $data['characters'] = $characters;
            buildMessage("error", "Please select at least 1 character", "register/register_characters_v");
            $data['view']      = "register/register_characters_v";
            $data['no_header'] = 1;
            $this->load->view('main/_template_v', $data);
            return;
        }

        $this->load->model('register_model');
        if ($this->register_model->verifyCharacters($chars, $apikey, $vcode)) {
            $result = $this->register_model->createAccount($username, $password, $email, $apikey, $vcode, $reports, $chars);
            if ($result != "ok") {
                $data['message']    = $result;
                $data['notice']     = "error";
                $data['characters'] = $this->register_model->getCharacters($apikey, $vcode);
                $data['view']       = "register/register_characters_v";
                $data['no_header']  = 1;
                $this->load->view('main/_template_v', $data);
            } else {
                $data['message']   = "Account created sucessfully";
                $data['notice']    = "success";
                $data['view']      = "login/login_v";
                $data['no_header'] = 1;
                $this->load->view('main/_template_v', $data);
            }
        }

    }

}