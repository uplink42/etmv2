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
            $data['traderoutes'] = $this->listTradeRoutes($character_id);
            $data['stocklists'] = $this->getLists();
            
            $data['view']           = 'main/tradesimulator_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function listTradeRoutes($character_id)
    {
        $this->load->model('Login_model');
        if($this->Login_model->checkCharacter($character_id, $this->session->iduser)) {
            $this->load->model('TradeRoutes_model');
            $result = $this->TradeRoutes_model->getRoutes($this->session->iduser);

            return $result;
        }
    }

    public function getLists()
    {
        $this->load->model('StockLists_model');
        $lists = $this->StockLists_model->getStockLists($this->session->iduser);

        return $lists;
    }

   
}
