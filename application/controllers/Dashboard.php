<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->library('session');
    }

    public function index($character_id, $interval = 3, $aggregate = 0)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $chars = [];
            $char_names = [];
            if ($aggregate == true) {
                $this->load->model('Login_model');
                $characters = $this->Login_model->getCharacterList($user_id);

                $chars = $characters['aggr'];
                $char_names = $characters['char_names'];
            } else {
                $chars = null;
            }

            $character_list = $this->getCharacterList($this->session->iduser);

            $data['selected'] = "dashboard";
            $this->load->model('Dashboard_model');
            $data['pie_data']       = $this->Dashboard_model->getPieData($character_id, $chars);
            $data['week_profits']   = $this->Dashboard_model->getWeekProfits($character_id, $chars);
            $data['new_info']       = $this->Dashboard_model->getNewInfo($character_id, $chars);
            $data['profits']        = $this->Dashboard_model->getProfits($character_id, $interval, $chars);
            $data['profits_trends'] = $this->Dashboard_model->getTotalProfitsTrends($character_id, $chars);
            $data['character_list'] = $character_list;
            //die(print_r($character_list));
            $data['interval']       = $interval;
            $data['aggregate'] = $aggregate;
            $data['char_names'] = $char_names;

            $this->load->model('Login_model');
            $data['character_name'] = $this->Login_model->getCharacterName($character_id);
            $data['character_id']   = $character_id;
            $data['view']           = 'main/dashboard_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
