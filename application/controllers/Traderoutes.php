<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class TradeRoutes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "traderoutes";
        $this->load->model('TradeRoutes_model');
    }

    /**
     * Loads the trade routes page
     * @param  int    $character_id 
     * @return void           
     */
    public function index($character_id) : void
    {
        $this->Log->addEntry("visit " . $this->page, $this->user_id);
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);

            $data['selected'] = "traderoutes";
            $data['view'] = 'main/traderoutes_v';

            $data['layout']['page_title']     = "Trade Routes";
            $data['layout']['icon']           = "pe-7s-plane";
            $data['layout']['page_aggregate'] = false;

            $this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Queries stations by name and echoes
     * the result back to the client
     * @return string json
     */
    public function searchStations() : void
    {
        $input = $_REQUEST['term'];
        $result = $this->TradeRoutes_model->queryStations($input);
        echo json_encode($result);
    }

    /**
     * Creates a new trade route and echoes the
     * result back to the client
     * @param  int    $character_id 
     * @return string json            
     */
    public function submitRoute(int $character_id) : void
    {
        if ($this->ValidateRequest->checkCharacterBelong($character_id, $this->user_id)) {
            if (!empty($_REQUEST['origin']) && !empty($_REQUEST['destination'])) {
                substr($_REQUEST['origin'], 0, 10) == "TRADE HUB:" ?
                $origin = substr($_REQUEST['origin'], 11) : $origin = $_REQUEST['origin'];
                substr($_REQUEST['destination'], 0, 10) == "TRADE HUB:" ?
                $destination = substr($_REQUEST['destination'], 11) : $destination = $_REQUEST['destination'];
                $data = $this->TradeRoutes_model->insertRoute($this->user_id, $origin, $destination);
            } else {
                $data['message'] = Msg::STATION_NOT_FOUND;
                $data['notice']  = "error";
            }
        } else {
            $data['message'] = Msg::INVALID_REQUEST;
            $data['notice']  = "error";
        }
        echo json_encode($data);
    }

    /**
     * Gets a list of a user's traderoutes and echoes
     * the result back to the client
     * @param  int    $character_id 
     * @return string json            
     */
    public function listTradeRoutes(int $character_id) : void
    {
        if ($this->ValidateRequest->checkCharacterBelong($character_id, $this->user_id)) {
            $result = $this->TradeRoutes_model->getRoutes($this->user_id);
            echo json_encode($result);
        }
    }

    /**
     * Deletes a trade route and echoes
     * the result back to the client
     * @param  int    $id_route 
     * @return string json         
     */
    public function deleteRoute(int $id_route) : void
    {
        if ($this->ValidateRequest->checkTradeRouteOwnership($id_route, $this->user_id)) {
            if ($this->TradeRoutes_model->deleteRoute($id_route)) {
                $data['message'] = Msg::ROUTE_REMOVE_SUCCESS;
                $data['notice']  = "success";
            } else {
                $data['message'] = Msg::ROUTE_REMOVE_ERROR;
                $data['notice']  = "error";
            }
        } else {
            $data['message'] = Msg::INVALID_REQUEST;
            $data['notice']  = "error";
        }
        echo json_encode($data);
    }
}
