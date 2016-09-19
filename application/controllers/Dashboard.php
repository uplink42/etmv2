<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->load->library('session');
        $this->page = "Dashboard";
    }

    //returns all dashboard information to the relevant view
    public function index($character_id, $interval = 3)
    {
        if ($interval > 7) {
            $interval = 7;
        }

        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "dashboard";
            $data['interval'] = $interval;

            $this->load->model('Dashboard_model');
            $profits = $this->Dashboard_model->getProfits($interval, $chars);

            $count = $profits['count'];
            if ($count > 200) {
                $img = false;
            } else {
                $img = true;
            }


            $data['pie_data']     = $this->Dashboard_model->getPieData($chars);
            $data['week_profits'] = $this->Dashboard_model->getWeekProfits($chars);
            $data['new_info']     = $this->Dashboard_model->getNewInfo($chars);

            $data['img']            = $img;
            $data['profits']        = $profits['result'];
            $data['profits_trends'] = $this->Dashboard_model->getTotalProfitsTrends($chars);

            $data['view'] = 'main/dashboard_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
