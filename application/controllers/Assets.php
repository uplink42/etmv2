<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assets extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index($character_id, $region_id = 0)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);

            $chars = $data['chars'];

            $data['selected'] = "assets";
            $this->load->model('Assets_model');
            $evolution = $this->Assets_model->getAssetEvolution($chars);
            
            $graph = "[";
            for ($i = 0; $i <= count($evolution) - 1; $i++) {
                $graph .= $evolution[$i]['a'];
                if ($i < count($evolution) - 1) {
                    $graph .= ",";
                }
            }
            $graph .= "]";

            $asset_totals = $this->Assets_model->getRegionData($chars);
            $region_name = $this->Assets_model->getRegionName($region_id);
            $asset_list = $this->Assets_model->getAssetsList($region_id, $chars);
            
            if($region_name != "All") {
                $data['current_asset_value'] = $asset_totals[$region_name][0]['total_value'];
            } else {
                $data['current_asset_value'] = $this->Assets_model->getCurrentAssetTotals($chars);
            }
            
            

            $data['asset_list'] = $asset_list;
            $data['region_name'] = $region_name;
            $data['region_id'] = $region_id;
            $data['totals'] = $asset_totals;
            $data['graph_data'] = $graph;
            $data['view']           = 'main/assets_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
