<?php
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');
defined('BASEPATH') or exit('No direct script access allowed');

class Profits extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "profits";
        $this->load->model('Profits_model', 'profits');
    }


    /**
     * Loads the Profits page
     * @param  int         $character_id 
     * @param  int|integer $interval     
     * @param  int|null    $item_id     
     * @return void                   
     */
    public function index(int $character_id, int $interval = 7, int $item_id = null) : void
    {
        if ($interval > 365) {
            $interval = 365;
        }
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            // profits table
            $profits   = $this->profits->getProfits($chars, $interval, $item_id, $this->user_id);
            $profits_r = $profits['result'];
            $profits_c = $profits['count'];

            if ($profits_c > 200) {
                $img = false;
            } else {
                $img = true;
            }

            $data['selected'] = "profits";
            $data['img']      = $img;
            $data['profits']  = $profits_r;
            $data['interval'] = $interval;
            $data['item_id']  = $item_id;
            $data['view']     = 'main/profits_v';

            $data['layout']['page_title']     = "Profit Breakdown";
            $data['layout']['icon']           = "pe-7s-graph1";
            $data['layout']['page_aggregate'] = true;

            $this->twig->display('main/_template_v', $data);
        }
    }


    public function getProfitChart(int $character_id, int $interval = 1, int $item_id = null)
    {
        echo $this->buildChart($character_id, 'getProfitChartData', 'Profits_model', $interval, $item_id); 
    }
}
