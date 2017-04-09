<?php
ini_set('max_execution_time', '-1');
defined('BASEPATH') or exit('No direct script access allowed');

class Tradefinder extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "tradefinder";
    }

    /**
     * Loads the networth tracker page
     * @param  int         $character_id
     * @param  int|integer $interval     
     * @return void                    
     */
    public function index(int $character_id) : void
    {
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];
            $data['selected'] = "tradefinder";
            $data['view']     = 'main/tradefinder_v';
            $this->load->model('Tradefinder_model', 'tf');

            $this->tf->createHistory();

            //$list = $this->tf->getAllItems();
            $data['results'] = $this->tf->findDeals('60003760', '60008494', $list, 0.3);
            $data['layout']['page_title']     = "Trade Finder";
            $data['layout']['icon']           = "pe-7s-graph3";
            $data['layout']['page_aggregate'] = true;
            //$this->twig->display('main/_template_v', $data);
        }
    }

}
