<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TradeSimulator extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->page = "TradeSimulator";
    }

    private $stationFrom;
    private $stationTo;

    public function index($character_id, $msg = null, $res = null)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);       
            $data['selected'] = "tradesimulator";
            $data['traderoutes'] = $this->listTradeRoutes($character_id);
            $data['stocklists'] = $this->getLists();

            if(isset($msg)) {
                buildMessage($msg['notice'], $msg['message'], 'main/tradesimulator_v');
            }

            if(isset($res)) {
                $data['results'] = $res;
            }

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

    public function process($character_id)
    {
        if(!empty($_REQUEST['origin-station']) &&
           !empty($_REQUEST['buy-method']) &&
           !empty($_REQUEST['buyer']) &&
           !empty($_REQUEST['destination-station']) &&
           !empty($_REQUEST['sell-method']) &&
           !empty($_REQUEST['seller']) &&
           !empty($_REQUEST['stocklist'])) {

            $this->load->model('TradeSimulator_model');
            $this->stationFrom = $this->TradeSimulator_model->getStationID($_REQUEST['origin-station']);
            $this->stationTo = $this->TradeSimulator_model->getStationID($_REQUEST['destination-station']);

            if($this->stationFrom && $this->stationTo) {
                $res = $this->TradeSimulator_model->init($_REQUEST['origin-station'],
                                                  $_REQUEST['destination-station'],
                                                  $_REQUEST['buyer'],
                                                  $_REQUEST['seller'],
                                                  $_REQUEST['buy-method'],
                                                  $_REQUEST['sell-method'],
                                                  $_REQUEST['stocklist']);
                $this->index($character_id, null, $res);

            } else {
                $msg = array("notice" => "error", "message" => "Invalid stations provided");
                $this->index($character_id, $msg);
            }
        } else {
            $msg = array("notice" => "error", "message" => "Missing Information");
            $this->index($character_id, $msg);
        }
    }



   
}
