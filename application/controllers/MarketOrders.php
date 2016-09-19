<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MarketOrders extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->load->library('session');
        $this->page = "MarketOrders";
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "marketorders";

            $this->load->model('MarketOrders_model');
            $transactions = $this->MarketOrders_model->getMarketOrders($chars);
            
            $data['view']           = 'main/marketorders_v';
            $this->load->view('main/_template_v', $data);
        }
    }


}
