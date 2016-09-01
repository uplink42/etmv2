<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assets extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->library('session');
    }

    public function index($character_id, $aggregate = 0)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

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

            $character_list = $this->getCharacterList($this->session->iduser);

            $data['selected'] = "assets";
            $this->load->model('Dashboard_model');

            $data['aggregate']  = $aggregate;
            $data['char_names'] = $char_names;
            $this->load->model('Login_model');

            $data['character_list'] = $character_list;
            $data['character_name'] = $this->Login_model->getCharacterName($character_id);
            $data['character_id']   = $character_id;
            $data['view']           = 'main/assets_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
