<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $aggregate;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    //logs off if either session or character request is invalid
    protected function enforce($character_id, $user_id = "")
    {
        $this->load->model('Login_model');
        if ($this->Login_model->checkSession() &&
            $this->Login_model->checkCharacter($character_id, $user_id)) {

            if(isset($_GET['aggr'])) {
                $aggr = $_GET['aggr'];

                if ($aggr != 1 && $aggr != 0) {
                    $this->aggregate = 0;
                } else {
                    $this->aggregate = $aggr;
                }
            }
            return true;
        } else {
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

    protected function getCharacterList($user_id)
    {
        $this->load->model('Login_model');
        $data = $this->Login_model->getCharacterList($user_id);
        return $data;
    }

    protected function loadViewDependencies($character_id, $user_id, $aggregate)
    {
        $chars      = [];
        $char_names = [];

        if ($aggregate == true) {
            $this->load->model('Login_model');
            $characters = $this->Login_model->getCharacterList($user_id);

            $chars      = $characters['aggr'];
            $char_names = $characters['char_names'];
        } else {
            $chars = "(" . $character_id . ")";
        }

        $data['chars']          = $chars;
        $character_list         = $this->getCharacterList($this->session->iduser);
        $data['aggregate']      = $aggregate;
        $data['char_names']     = $char_names;
        $data['character_list'] = $character_list;

        $this->load->model('Login_model');
        $data['character_name'] = $this->Login_model->getCharacterName($character_id);
        $data['character_id']   = $character_id;
        return $data;
    }

}
