<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TradeSimulator extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');

    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);

            $data['selected'] = "tradesimulator";
            
            $data['view']           = 'main/tradesimulator_v';
            $this->load->view('main/_template_v', $data);
        }
    }

   
}
