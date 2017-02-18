<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_pricedata_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPrices(): int
    {
        $url      = "https://crest-tq.eveonline.com/market/prices/";
        $response = json_decode(file_get_contents($url), true);

        $this->db->select('max(eve_iditem) as max');
        $query = $this->db->get('item');
        $maxID = $query->row()->max;

        $priceData = array();

        for ($i = 0; $i < count($response['items']); $i++) {
            $typeID                                                = $response['items'][$i]['type']['id_str'];
            isset($response['items'][$i]['averagePrice']) ? $price = $response['items'][$i]['averagePrice'] : $price = 0;

            array_push($priceData, array("item_eve_iditem" => $typeID, "price_evecentral" => $price));
        }

        //hardcoded item prices
        $query          = $this->db->get('fixed_prices');
        $result         = $query->result();
        $fixedPriceData = array();

        foreach ($result as $price) {
            array_push($fixedPriceData, array("item_eve_iditem" => $price->item_eve_iditem, "price_evecentral" => $price->price));
        }

        $this->db->trans_start();

        $this->db->empty_table('item_price_data');
        $this->db->insert_batch('item_price_data', $priceData);
        $this->db->query(
            chunk("item_price_data",
                array('item_eve_iditem', 'price_evecentral'), $fixedPriceData)
        );
        //set bpcs as 0
        $this->db->query("UPDATE item_price_data JOIN item ON item.eve_iditem = item_price_data.item_eve_iditem SET item_price_data.price_evecentral = 0 WHERE item.name LIKE '%blueprint%'");

        $this->db->trans_complete();

        if ($this->db->trans_status() === true) {
            $count = count($priceData);
            return $count;
        }
    }

}
