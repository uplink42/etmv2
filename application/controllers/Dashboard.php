<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {

        parent::__construct();
        $this->load->library('session');
    }

    public function index($character_id, $interval = 1)
    {
        if ($this->enforce($character_id, $this->session->iduser)) {

           if($character_id == "all") {
                //get all account characters
           }

            $data['selected'] = "dashboard";
            $this->load->model('Dashboard_model');
            $data['pie_data']     = $this->Dashboard_model->getPieData($character_id);
            $data['week_profits'] = $this->Dashboard_model->getWeekProfits($character_id);
            $data['new_info']     = $this->Dashboard_model->getNewInfo($character_id);
            $data['profits']       = $this->Dashboard_model->getProfits($character_id, $interval);
            $data['profits_trends'] = $this->Dashboard_model->getTotalProfitsTrends($character_id);
            $data['character_list'] = $this->getCharacterList($this->session->iduser);

            $this->load->model('Login_model');
            $data['character_name'] = $this->Login_model->getCharacterName($character_id);
            $data['character_id'] = $character_id;
            $data['view']         = 'main/dashboard_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
