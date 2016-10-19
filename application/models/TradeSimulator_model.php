<?php declare (strict_types = 1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TradeSimulator_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/RateLimiter');
    }

    private $stationFromName;
    private $stationToName;
    private $stationFromID;
    private $stationToID;

    private $characterFrom;
    private $characterTo;
    private $characterFromName;
    private $characterToName;

    private $characterFromMethod;
    private $characterToMethod;

    private $brokerFeeFrom;
    private $transTaxFrom;
    private $brokerFeeTo;
    private $transTaxTo;

    private $stockListID;
    private $stockListName;

    public function getStationID(string $station_name)
    {
        $this->db->select('eve_idstation');
        $this->db->where('name', $station_name);
        $query = $this->db->get('station');

        if ($query->num_rows() != 0) {
            $result = $query->row();
        } else {
            $result = false;
        }

        return $result;
    }

    public function init(string $origin, string $destination, int $buyer, int $seller, string $buy_method, string $sell_method, int $stocklist)
    {
        $this->stationFromName     = (string) $origin;
        $this->stationToName       = (string) $destination;
        $this->stationFromID       = (int) $this->getStationID($origin)->eve_idstation;
        $this->stationToID         = (int) $this->getStationID($destination)->eve_idstation;
        $this->characterFrom       = (string) $buyer;
        $this->characterTo         = (string) $seller;
        $this->characterFromName   = (string) $this->getCharacterName($buyer);
        $this->characterToName     = (string) $this->getCharacterName($seller);
        $this->characterFromMethod = (string) $buy_method;
        $this->characterToMethod   = (string) $sell_method;
        $this->stocklistID         = (int) $stocklist;
        $this->stockListName       = (string) $this->getStockListName($stocklist)->name;

        $CI = &get_instance();
        $CI->load->model('Tax_Model');
        $CI->Tax_Model->tax($this->stationFromID, $this->stationToID, $buyer, $seller, $buy_method, $sell_method);
        $this->transTaxFrom  = $CI->Tax_Model->calculateTaxFrom();
        $this->brokerFeeFrom = $CI->Tax_Model->calculateBrokerFrom();
        $this->transTaxTo    = $CI->Tax_Model->calculateTaxTo();
        $this->brokerFeeTo   = $CI->Tax_Model->calculateBrokerTo();

        return $this->generateResults();
    }

    private function generateResults(): array
    {
        $contents = $this->getStockListContents();

        $results = [];

        $buy_broker_per  = ($this->brokerFeeFrom - 1) * 100;
        $buy_tax_per     = ($this->transTaxFrom - 1) * 100;
        $sell_broker_per = (1 - $this->brokerFeeTo) * 100;
        $sell_tax_per    = (1 - $this->transTaxTo) * 100;

        $taxes = [
            "list"           => $this->stockListName,
            "buy_character"  => $this->characterFromName,
            "sell_character" => $this->characterToName,
            "buy_station"    => $this->stationFromName,
            "sell_station"   => $this->stationToName,
            "buy_method"     => $this->characterFromMethod,
            "sell_method"    => $this->characterToMethod,
            "buy_broker"     => $buy_broker_per,
            "buy_tax"        => $buy_tax_per,
            "sell_broker"    => $sell_broker_per,
            "sell_tax"       => $sell_tax_per,
        ];

        foreach ($contents as $row) {
            $this->RateLimiter->rateLimit();

            $item_id                   = (int) $row->id;
            $item_name                 = $row->name;
            $row->vol == 0 ? $item_vol = 1 : $item_vol = $row->vol;

            $best_buy_price  = $this->getCrestData($item_id, $this->stationFromID, $this->characterFromMethod);
            $buy_broker_fee  = $best_buy_price * ($this->brokerFeeFrom - 1);
            $best_sell_price = $this->getCrestData($item_id, $this->stationToID, $this->characterToMethod);
            $sell_broker_fee = $best_sell_price * (1 - $this->brokerFeeTo);
            $sell_trans_tax  = $best_sell_price * (1 - $this->transTaxTo);

            $best_buy_price_taxed  = $best_buy_price * $this->brokerFeeFrom;
            $best_sell_price_taxed = $best_sell_price * $this->brokerFeeTo * $this->transTaxTo;

            $profit_raw                                 = $best_sell_price_taxed - $best_buy_price_taxed;
            $profit_m3                                  = $profit_raw / $item_vol;
            $best_buy_price_taxed != 0 ? $profit_margin = ($profit_raw / $best_buy_price_taxed) * 100 : $profit_margin = 0;

            $item_res = array("id" => $item_id,
                "name"                 => $item_name,
                "vol"                  => $item_vol,
                "buy_price"            => $best_buy_price,
                "buy_broker"           => $buy_broker_fee,
                "sell_price"           => $best_sell_price,
                "sell_broker"          => $sell_broker_fee,
                "sell_tax"             => $sell_trans_tax,
                "profit_raw"           => $profit_raw,
                "profit_m3"            => $profit_m3,
                "profit_margin"        => $profit_margin);

            array_push($results, $item_res);
        }

        return array("results" => $results, "req" => $taxes);
    }

    private function getStockListContents(): array
    {
        $list = $this->stocklistID;

        $this->db->select('i.eve_iditem as id, i.name as name, i.volume as vol');
        $this->db->from('itemlist il');
        $this->db->join('itemcontents ic', 'ic.itemlist_iditemlist = il.iditemlist');
        $this->db->join('item i', 'i.eve_iditem = ic.item_eve_iditem');
        $this->db->where('il.iditemlist', $list);
        $query  = $this->db->get();
        $result = $query->result();

        return $result;
    }

    private function getCrestData(int $item_id, int $station_id, string $order_type): float
    {
        $regionID = $this->getRegionID($station_id)->id;
        $url      = "https://crest-tq.eveonline.com/market/" . $regionID . "/orders/" . $order_type . "/?type=https://crest-tq.eveonline.com/inventory/types/" . $item_id . "/";
        $result   = json_decode(file_get_contents($url), true);

        $array_prices = [];

        for ($i = 0; $i < count($result['items']); $i++) {
            //only fetch orders FROM the designated stationID
            if ($result['items'][$i]['location']['id_str'] == $station_id) {
                array_push($array_prices, $result['items'][$i]['price']);
            }
        }

        if ($order_type == 'buy') {
            if (!empty($array_prices)) {
                $price = max($array_prices);
            } else {
                $price = 0;
            }
        } else if ($order_type == 'sell') {
            if (!empty($array_prices)) {
                $price = min($array_prices);
            } else {
                $price = 0;
            }
        }
        return $price;
    }

    private function getRegionID(int $station_id): stdClass
    {
        $this->db->select('r.eve_idregion as id');
        $this->db->from('region r');
        $this->db->join('system sys', 'sys.region_eve_idregion = r.eve_idregion');
        $this->db->join('station st', 'st.system_eve_idsystem = sys.eve_idsystem');
        $this->db->where('st.eve_idstation', $station_id);
        $query  = $this->db->get();
        $result = $query->row();

        return $result;
    }

    private function getStockListName(int $stocklist): stdClass
    {
        $this->db->select('name');
        $this->db->where('iditemlist', $stocklist);
        $query = $this->db->get('itemlist');

        $result = $query->row();
        return $result;
    }

    private function getCharacterName(int $id_character): string
    {
        $this->db->select('name');
        $this->db->where('eve_idcharacter', $id_character);
        $query = $this->db->get('characters');

        $result = $query->row()->name;
        return $result;
    }

}
