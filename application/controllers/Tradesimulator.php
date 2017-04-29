<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class TradeSimulator extends MY_Controller
{
    private $stationFrom;
    private $stationTo;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "tradesimulator";
        $this->load->model('TradeSimulator_model', 'ts');
    }

    /**
     * Loads the trade simulator page
     * @param  int        $character_id 
     * @param  array|null $msg            error msg from previous page       
     * @param  [type]     $res            display results state?
     * @return void         
     */
    public function index($character_id, $res = null) : void
    {
        $this->Log->addEntry("visit " . $this->page, $this->user_id);
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $data['selected'] = "tradesimulator";
            $this->load->model('common/ValidateRequest');
            $data['status'] = $this->ValidateRequest->getCrestStatus();

            if ($data['status']) {
                $data['traderoutes'] = $this->listTradeRoutes($character_id);
                $data['stocklists']  = $this->getLists();
                $data['results']     = $res;
            } else {
                $data["notice"]  = "error";
                $data["message"] = Msg::CREST_CONNECT_FAILURE;
            }

            $data['layout']['page_title']     = "Trade Simulator";
            $data['layout']['icon']           = "pe-7s-magic-wand";
            $data['layout']['page_aggregate'] = false;

            $data['view'] = 'main/tradesimulator_v';
            $this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Gets all trade routes for this user
     * @param  int    $character_id 
     * @return array               
     */
    public function listTradeRoutes(int $character_id): array
    {
        if ($this->ValidateRequest->checkCharacterBelong($character_id, $this->user_id)) {
            $this->load->model('TradeRoutes_model');
            $result = $this->TradeRoutes_model->getRoutes($this->user_id);
            return $result;
        }
    }

    /**
     * Gets all stock lists for this user
     * @return [type] [description]
     */
    public function getLists(): array
    {
        $this->load->model('StockLists_model');
        $lists = $this->StockLists_model->getStockLists($this->user_id);
        return $lists;
    }

    /**
     * Begins the price check
     * @param  int    $character_id 
     * @return string json          
     */
    public function process(int $character_id) : void
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
            $buyer               = (int)    $_REQUEST['buyer'];
            $seller              = (int)    $_REQUEST['seller'];
            $buy_method          = (string) $_REQUEST['buy-method'];
            $sell_method         = (string) $_REQUEST['sell-method'];
            $stocklist           = (int)    $_REQUEST['stocklist'];

            $this->stationFrom = $this->ts->getStationID($_REQUEST['origin-station']);
            $this->stationTo   = $this->ts->getStationID($_REQUEST['destination-station']);

            if ($this->stationFrom && $this->stationTo) {
                $res = $this->ts->init(
                    $origin_station,
                    $destination_station,
                    $buyer,
                    $seller,
                    $buy_method,
                    $sell_method,
                    $stocklist,
                    $this->user_id);
                $this->load->model('common/Log');
                $this->Log->addEntry('tradesim', $this->user_id);
                $this->index($character_id, $res);
            } else {
                buildMessage('success', Msg::STATION_NOT_FOUND);
                $msg = array("notice" => "error", "message" => Msg::STATION_NOT_FOUND);
                $this->index($character_id);
            }
        } else {
            buildMessage('success', Msg::MISSING_INFO);
            $msg = array("notice" => "error", "message" => Msg::MISSING_INFO);
            $this->index($character_id);
        }
    }
}
