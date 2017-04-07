<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assets extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "assets";
    }

    /**
     * Loads the assets page
     * @param  int         $character_id 
     * @param  int|integer $region_id    
     * @return void                    
     */
    public function index(int $character_id, int $region_id = 0) : void
    {
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "assets";
            $this->load->model('Assets_model', 'assets');
            $evolution = $this->assets->getAssetEvolution($chars);

            $graph = "[";
            for ($i = 0; $i <= count($evolution) - 1; $i++) {
                $graph .= $evolution[$i]['a'];
                if ($i < count($evolution) - 1) {
                    $graph .= ",";
                }
            }
            $graph .= "]";

            $asset_totals = $this->assets->getRegionData($chars);
            $chart        = $this->assets->buildAssetDistributionChart($asset_totals);
            $region_name  = $this->assets->getRegionName($region_id);
            if ($region_name != "All") {
                $data['current_asset_value'] = isset($asset_totals[$region_name]) ? $asset_totals[$region_name][0]['total_value'] : 0;
            } else {
                $data['current_asset_value'] = $this->assets->getCurrentAssetTotals($chars);
            }

            $data['region_name'] = $region_name;
            $data['region_id']   = $region_id;
            //$data['totals']      = $asset_totals;
            $data['graph_data']  = $graph;

            $data['layout']['page_title']     = "Assets";
            $data['layout']['icon']           = "pe-7s-plugin";
            $data['layout']['page_aggregate'] = true;

            $data['view']        = 'main/assets_v';
            $this->twig->display('main/_template_v', $data);
        }
    }


    public function getAssetsTable(int $character_id, bool $aggr)
    {
        $region_id = $_REQUEST['region_id'] ?? null;
        $params = ['region_id'  => $region_id ];

        echo $this->buildData($character_id, $aggr, 'getAssetsList', 'Assets_model', $params); 
    }


    public function getAssetsDistributionChart(int $character_id, bool $aggr)
    {
        $params = [];
        echo $this->buildData($character_id, $aggr, 'buildAssetDistributionChart', 'Assets_model', $params); 
    }
}
