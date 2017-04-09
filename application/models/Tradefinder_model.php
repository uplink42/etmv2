<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tradefinder_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generates autocomplete results for Citadel searches
     * @param  string $input [description]
     * @return array        
     */
    
    public function getAllItems()
    {
        $this->db->select('item_eve_iditem', 'id');
        $this->db->where('price_evecentral >', 0);
        $query = $this->db->get('item_price_data');
        return $query->result();
    }

    public function findDeals($fromStationID, $toStationID, $list, $minMargin)
    {
        $this->load->model('Tradesimulator_model', 'ts');
        $fromRegionID = $this->ts->getRegionID($fromStationID)->id;
        $toRegionID = $this->ts->getRegionID($toStationID)->id;

        $results = [];
        //60003760
        //60008494
        for ($i = 0, $max = count($list); $i < $max; $i++) {
            log_message('error', $i);
            $buy_url = "https://crest-tq.eveonline.com/market/" . $fromRegionID . "/orders/buy" . "/?type=https://crest-tq.eveonline.com/inventory/types/" . $list[$i]->item_eve_iditem . "/";

            $buy_results_region = json_decode(file_get_contents($buy_url), true);
            $buy_results_station = [];

            for ($k = 0; $k < count($buy_results_region); $k++) {
                // only fetch orders FROM the designated stationID
                if ($buy_results_region['items'][$k]['location']['id_str'] == $fromStationID) {
                    array_push($buy_results_station, $buy_results_region['items'][$k]['price']);
                }
            }

            $highest_buy_station = max($buy_results_station);

            // selling
            $sell_url = "https://crest-tq.eveonline.com/market/" . $toRegionID . "/orders/sell" . "/?type=https://crest-tq.eveonline.com/inventory/types/" . $list[$i]->item_eve_iditem . "/";

            $sell_results_region = json_decode(file_get_contents($sell_url), true);
            $sell_results_station = [];

            for ($k = 0; $k < count($sell_results_region); $k++) {
                // only fetch orders FROM the designated stationID
                if ($sell_results_region['items'][$k]['location']['id_str'] == $toStationID) {
                    array_push($sell_results_station, $sell_results_region['items'][$k]['price']);
                }
            }

            $lowest_sell_station = min($sell_results_station);
            $difference = $lowest_sell_station - $highest_buy_station;
            log_message('error', $difference);
            $profit_margin = (($lowest_sell_station - $highest_buy_station) / $highest_buy_station) * 100;
            
            if ($profit_margin > 0.3 && $highest_buy_station > 1000000) {
                $volume = 
                $remaining = $max - $i;
                log_message('error', $i . ' added to list.' . $remaining . ' items to search');
                array_push($results, $profit_margin);
            }
        }

        return $results;
    }



    public function createHistory()
    {
        $this->db->where('type >', '0');
        $query = $this->db->get('item');
        $items = $query->result();

        $this->db->where('isKS', '1');
        $query = $this->db->get('region');
        $regions = $query->result();

        //each item
        for ($i = 210, $max = count($items); $i < $max; $i++) {
            if ($items[$i]->eve_iditem != $items[$i+1]->eve_iditem) {
                log_message('error', $items[$i] . ' done');
            }
            //each region
            for ($k = 0, $max = count($regions); $k < $max; $k++) {
                //get history
                $url = "https://crest-tq.eveonline.com/market/".$regions[$k]->eve_idregion."/history/?type=https://crest-tq.eveonline.com/inventory/types/".$items[$i]->eve_iditem."/";
                $results = json_decode(file_get_contents($url), true);

                for ($j = 0; $j < count($results['items']); $j++) {
                    $date = substr($results['items'][$j]['date'],0, 10);
                    $data = [
                        'date' => $date,
                        'volume' => $results['items'][$j]['volume'],
                        'orders' => $results['items'][$j]['orderCount'],
                        'lowest' => $results['items'][$j]['lowPrice'],
                        'avg' => $results['items'][$j]['avgPrice'],
                        'highest' => $results['items'][$j]['highPrice'],
                        'region' => $regions[$k]->eve_idregion,
                        'item' => $items[$i]->eve_iditem
                    ];

                    $datestr = strtotime($date);
                    $dateStart = 1456790400; //01 03 2016
                    $dateEnd = 1483142400; //2016 12 31

                    if (/*$date < $dateEnd*/true) {
                        $this->db->replace('market_history', $data);
                    }
                }
            }
        }
    }
}
