<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MarketOrders extends MY_Controller
{
    private $significant;
    private $check;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->page = "MarketOrders";

        if(!empty($_REQUEST['check'])) {
            $this->check = $_REQUEST['check'];
        } else {
            $this->check = 0;
        }
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "marketorders";

            $this->load->model('MarketOrders_model');
            $orders_buy = $this->injectIcons($this->MarketOrders_model->getMarketOrders($chars, "buy", $this->check));
            $orders_sell = $this->injectIcons($this->MarketOrders_model->getMarketOrders($chars, "sell", $this->check));


            $data['buyorders'] = $orders_buy;
            $data['sellorders'] = $orders_sell;
            $data['view']           = 'main/marketorders_v';
            $this->load->view('main/_template_v', $data);
        }
    }


}
