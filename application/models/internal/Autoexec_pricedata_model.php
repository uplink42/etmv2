<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Autoexec_pricedata_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getPrices()
    {
        $url = "https://crest-tq.eveonline.com/market/prices/";
        $response = json_decode(file_get_contents($url),true);

        $this->db->select('max(eve_iditem) as max');
        $query = $this->db->get('item');
        $maxID = $query->row()->max;

        $priceData = array();

        for($i=0; $i<count($response['items']); $i++)
        {
            $typeID = $response['items'][$i]['type']['id_str'];
            isset($response['items'][$i]['averagePrice']) ? $price = $response['items'][$i]['averagePrice'] : $price = 0;
            
            array_push($priceData, array("item_eve_iditem" => $typeID, "price_evecentral" => $price));
        }

        $this->db->trans_start();
        $this->db->empty_table('item_price_data');
        $this->db->insert_batch('item_price_data', $priceData);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $count = count($priceData);
            return $count;
        }
        //var_dump($priceList);


    }
}
