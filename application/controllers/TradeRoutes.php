<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TradeRoutes extends MY_Controller
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
            $chars = $data['chars'];

            $data['selected'] = "traderoutes";
            
            $data['view']           = 'main/traderoutes_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function searchStations()
    {
        $input = $_REQUEST['term'];

        $this->load->model('TradeRoutes_model');
        $result = $this->TradeRoutes_model->queryStations($input);

        echo json_encode($result);

    }

    public function submitRoute($character_id)
    {   
        $this->load->model('Login_model');
        if($this->Login_model->checkCharacter($character_id, $this->session->iduser)) {
           $this->load->model('TradeRoutes_model');
           if (!empty($_REQUEST['origin']) && !empty($_REQUEST['destination'])) {
                $origin = $_REQUEST['origin'];
                $destination = $_REQUEST['destination'];
                $data = $this->TradeRoutes_model->insertRoute($this->session->iduser, $origin, $destination);
           } else {
                $data['message']   = "Missing stations provided";
                $data['notice']    = "error";
           }
        } else {
            $data['message']   = "Invalid request";
            $data['notice']    = "error";
        }

        echo json_encode($data);
    }

    public function listTradeRoutes($character_id)
    {
        $this->load->model('Login_model');
        if($this->Login_model->checkCharacter($character_id, $this->session->iduser)) {
            $this->load->model('TradeRoutes_model');
            $result = $this->TradeRoutes_model->getRoutes($this->session->iduser);

            echo json_encode($result);
        }
    }

    public function deleteRoute($id_route)
    {
        $this->load->model('TradeRoutes_model');
        if ($this->TradeRoutes_model->checkRouteBelong($id_route, $this->session->iduser)) {
            if($this->TradeRoutes_model->deleteRoute($id_route)) {
                $data['message']   = "Trade Route deleted successfully";
                $data['notice']    = "success";
            } else {
                $data['message']   = "Error. Try again";
                $data['notice']    = "error";
            }
        } else {
            $data['message']   = "Invalid request";
            $data['notice']    = "error";
        }

        echo json_encode($data);
    }
}
