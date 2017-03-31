<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '180');

class Marketorders extends MY_Controller
{
    private $significant;
    private $check;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "marketorders";

        $this->check = $_REQUEST['check'] ?? 0;
        settype($this->check, 'bool');
    }

    /**
     * Loads the Market orders page
     * @param  int    $character_id 
     * @return void               
     */
    public function index(int $character_id) : void
    {
        if ($this->enforce($character_id, $user_id = $this->user_id)) {
            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "marketorders";

            $this->load->model('MarketOrders_model');
            $orders_buy  = $this->injectIcons($this->MarketOrders_model->getMarketOrders($chars, "buy", $this->check));
            $orders_sell = $this->injectIcons($this->MarketOrders_model->getMarketOrders($chars, "sell", $this->check));

            if($this->check) {
                $this->load->model('common/Log');
                $this->Log->addEntry('ordercheck', $this->user_id);
            }

            $data['buyorders']  = $orders_buy;
            $data['sellorders'] = $orders_sell;
            $data['view']       = 'main/marketorders_v';
            $this->twig->display('main/_template_v', $data);
        }
    }
}
