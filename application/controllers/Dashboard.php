<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        $aggr = $_GET['aggr'];

        if ($aggr != 1 && $aggr != 0) {
            $this->aggregate = 0;
        } else {
            $this->aggregate = $aggr;
        }
    }

    //returns all dashboard information to the relevant view
    public function index($character_id, $interval = 3)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data  = $this->loadViewDependencies($character_id, $user_id, $aggregate);

            $chars = $data['chars'];

            $data['selected'] = "dashboard";
            $this->load->model('Dashboard_model');
            $data['interval'] = $interval;

            $data['pie_data']       = $this->Dashboard_model->getPieData($chars);
            $data['week_profits']   = $this->Dashboard_model->getWeekProfits($chars);
            $data['new_info']       = $this->Dashboard_model->getNewInfo($chars);
            $data['profits']        = $this->Dashboard_model->getProfits($interval, $chars);
            $data['profits_trends'] = $this->Dashboard_model->getTotalProfitsTrends($chars);

            $data['view'] = 'main/dashboard_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
