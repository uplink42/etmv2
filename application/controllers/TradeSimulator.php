<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tradesimulator extends MY_Controller
{
    private $stationFrom;
    private $stationTo;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "TradeSimulator";
        $this->load->model('TradeSimulator_model');
    }

    public function index(int $character_id, array $msg = null, $res = null)
    {
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $data['selected'] = "tradesimulator";
            $this->load->model('common/ValidateRequest');
            $data['status'] = $this->ValidateRequest->getCrestStatus();

            if ($data['status']) {
                $data['traderoutes'] = $this->listTradeRoutes($character_id);
                $data['stocklists']  = $this->getLists();

                if (isset($msg)) {
                    buildMessage($msg['notice'], $msg['message'], 'main/tradesimulator_v');
                }

                $res ? $data['results'] = $res : '';

            } else {
                $data["notice"]  = "error";
                $data["message"] = Msg::CREST_CONNECT_FAILURE;
            }

            $data['view'] = 'main/tradesimulator_v';
            $this->load->view('main/_template_v', $data);

        }
    }

    public function listTradeRoutes(int $character_id): array
    {
        if ($this->ValidateRequest->checkCharacterBelong($character_id, $this->user_id)) {
            $this->load->model('TradeRoutes_model');
            $result = $this->TradeRoutes_model->getRoutes($this->user_id);

            return $result;
        }
    }

    public function getLists(): array
    {
        $this->load->model('StockLists_model');
        $lists = $this->StockLists_model->getStockLists($this->user_id);

        return $lists;
    }

    public function process(int $character_id)
    {
        if (!empty($_REQUEST['origin-station']) &&
            !empty($_REQUEST['buy-method']) &&
            !empty($_REQUEST['buyer']) &&
            !empty($_REQUEST['destination-station']) &&
            !empty($_REQUEST['sell-method']) &&
            !empty($_REQUEST['seller']) &&
            !empty($_REQUEST['stocklist'])) {

            $this->load->model('common/ValidateRequest');
            $list_id = (int) $_REQUEST['stocklist'];
            $user_id = (int) $this->user_id;
            
            if(!$this->ValidateRequest->checkStockListOwnership($list_id, $user_id)) {
                $msg = array("notice" => "error", "message" => Msg::LIST_NOT_BELONG);
                $this->index($character_id, $msg);
            }

            $origin_station      = (string) $_REQUEST['origin-station'];
            $destination_station = (string) $_REQUEST['destination-station'];
            $buyer               = (int) $_REQUEST['buyer'];
            $seller              = (int) $_REQUEST['seller'];
            $buy_method          = (string) $_REQUEST['buy-method'];
            $sell_method         = (string) $_REQUEST['sell-method'];
            $stocklist           = (int) $_REQUEST['stocklist'];

            $this->stationFrom = $this->TradeSimulator_model->getStationID($_REQUEST['origin-station']);
            $this->stationTo   = $this->TradeSimulator_model->getStationID($_REQUEST['destination-station']);

            if ($this->stationFrom && $this->stationTo) {
                $res = $this->TradeSimulator_model->init(
                    $origin_station,
                    $destination_station,
                    $buyer,
                    $seller,
                    $buy_method,
                    $sell_method,
                    $stocklist);

                $this->load->model('common/Log');
                $this->Log->addEntry('tradesim', $this->user_id);
                $this->index($character_id, null, $res);

            } else {
                $msg = array("notice" => "error", "message" => Msg::STATION_NOT_FOUND);
                $this->index($character_id, $msg);
            }
        } else {
            $msg = array("notice" => "error", "message" => Msg::MISSING_INFO);
            $this->index($character_id, $msg);
        }
    }

}
