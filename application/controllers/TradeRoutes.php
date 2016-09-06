<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TradeRoutes extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];

            $data['selected'] = "assets";
            
            
            
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
                //$this->TradeRoutes_model->insertRoute();
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
}
