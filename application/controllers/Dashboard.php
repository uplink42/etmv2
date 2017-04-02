<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "dashboard";
        $this->load->model('Dashboard_model', 'dashboard');
    }

   /**
    * Loads the dashboard page
    * @param  int         $character_id 
    * @param  int|integer $interval     
    * @return void                    
    */
    public function index(int $character_id, int $interval = 3) : void
    {
        if ($interval > 7) {
            $interval = 7;
        }

        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "dashboard";
            $data['interval'] = $interval;
            $data['week_profits']   = $this->dashboard->getWeekProfits($chars);
            $data['new_info']       = $this->dashboard->getNewInfo($chars);
            $data['profits_trends'] = $this->dashboard->getTotalProfitsTrends($chars);

            $data['layout']['page_title']     = "Dashboard";
            $data['layout']['icon']           = "pe-7s-shield";
            $data['layout']['page_aggregate'] = true;

            $data['view'] = 'main/dashboard_v';
            $this->twig->display('main/_template_v', $data);
        }
    }


    public function getPieChart(int $character_id, bool $aggr = false) : void
    {
        $params = [];
        echo $this->buildData($character_id, $aggr, 'getPieChartData', 'Dashboard_model', $params); 
    }

    public function getProfitTable(int $character_id, int $interval = 3, bool $aggr = false) : void
    {
        $params = ['interval' => $interval,
                   'user_id'  => $this->user_id];
                   
        echo $this->buildData($character_id, $aggr, 'getProfits', 'Dashboard_model', $params); 
    }
}