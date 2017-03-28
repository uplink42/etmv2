<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "dashboard";
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

            $this->load->model('Dashboard_model');
            $profits = $this->Dashboard_model->getProfits($interval, $chars, $this->user_id);

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
            $data['profits']        = $this->injectIcons($profits['result']);
            $data['profits_trends'] = $this->Dashboard_model->getTotalProfitsTrends($chars);
            $data['view'] = 'main/dashboard_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
