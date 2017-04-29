<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class NetworthTracker extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "networthtracker";
    }

    /**
     * Loads the networth tracker page
     * @param  int         $character_id
     * @param  int|integer $interval     
     * @return void                    
     */
    public function index($character_id, $interval = 7) : void
    {
        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $this->user_id)) {
            $this->Log->addEntry("visit " . $this->page, $this->user_id);
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];
            $data['selected'] = "networth";
            $data['interval'] = $interval;
            $data['view']     = 'main/nwtracker_v';

            $data['layout']['page_title']     = "Net Worth Tracker";
            $data['layout']['icon']           = "pe-7s-graph3";
            $data['layout']['page_aggregate'] = true;
            $this->twig->display('main/_template_v', $data);
        }
    }


    public function getNetworthChart(int $character_id, int $interval, bool $aggr = false) : void
    {
        //echo $this->buildChart($character_id, $aggr, 'init', 'NetworthTracker_model', $interval);
        $params = ['interval' => $interval];
        echo $this->buildData($character_id, $aggr, 'init', 'NetworthTracker_model', $params); 
    }
}
