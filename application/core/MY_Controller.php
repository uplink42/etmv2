<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct(){
	
	parent::__construct();
	$this->load->library('session');
    }

    //logs off if either session or character request is invalid
    protected function enforce($character_id = "", $user_id = "") 
    {
	$this->load->model('Login_model');
	if ($this->Login_model->checkSession() &&
	    $this->Login_model->checkCharacter($character_id, $user_id)) {
	    return true;
	}
	$data['view'] = "login/login_v";
	buildMessage("error", "Invalid session or character request", $data['view']);
	$data['no_header'] = 1;
	
	$this->session->unset_userdata('username');
	$this->session->unset_userdata('start');
	$this->session->unset_userdata('iduser');
	$this->load->view('main/_template_v', $data);
	return false;
    }
}

