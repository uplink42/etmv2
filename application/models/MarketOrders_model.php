<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MarketOrders_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/RateLimiter');
    }

    /**
     * Returns a list of market orders, optionally filtered
     * by type and character set
     * @param  string       $chars 
     * @param  string       $type  
     * @param  bool|boolean $check perform pricecheck?
     * @return array              
     */
    public function getMarketOrders(string $chars, string $type, bool $check = false): array
    {
        $this->db->select('o.eve_item_iditem as item_id,
                           i.name as item_name,
                           s.eve_idstation as station_id,
                           s.name as station_name,
                           o.price as price_unit,
                           o.volume_remaining as vol,
                           (o.price*o.volume_remaining) as price_total,
                           o.escrow as escrow,
                           o.order_range as range,
                           o.date as date,
                           o.characters_eve_idcharacters as character_id,
                           c.name as character,
                           o.transkey as order_id,
                           r.eve_idregion as region_id,
                           sys.eve_idsystem as idsystem');
        $this->db->from('orders o');
        $this->db->join('item i', 'i.eve_iditem = o.eve_item_iditem', 'left');
        $this->db->join('station s', 's.eve_idstation = o.station_eve_idstation');
        $this->db->join('characters c', 'c.eve_idcharacter = o.characters_eve_idcharacters');
        $this->db->join('system sys', 'sys.eve_idsystem = s.system_eve_idsystem');
        $this->db->join('region r', 'r.eve_idregion = sys.region_eve_idregion');
        $this->db->where('o.order_state', 'open');
        $this->db->where('o.characters_eve_idcharacters IN ' . $chars);
        $this->db->where('o.type', $type);
        $query  = $this->db->get();
        $result = $query->result_array();

        if ($check) {
            for ($i = 0; $i < count($result); $i++) {
                $orderID              = (string) $result[$i]['order_id'];
                $stationID            = (string) $result[$i]['station_id'];
                $regionID             = (int) $result[$i]['region_id'];
                $itemID               = (int) $result[$i]['item_id'];
                $result[$i]['status'] = $this->checkOrder($orderID,
                    $stationID,
                    $regionID,
                    $type,
                    $itemID);
            }
        }
        return $result;
    }

    /**
     * Checks wether we should check an order or use the cached values instead
     * @param  string $order_id   
     * @param  string $station_id 
     * @param  int    $region_id  
     * @param  string $type       
     * @param  int    $item_id    
     * @return string            
     */
    public function checkOrder(string $order_id, string $station_id, int $region_id, string $type, int $item_id): string
    {
        $dt = new DateTime();
        $tz = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);

        $date_now = $dt->format('Y-m-d H:i:s');
        $time     = strtotime($date_now);
        $time     = $time - (6 * 60);
        $date     = date("Y-m-d H:i:s", $time); //6 minutes ago

        $this->db->select('timestamp_check');
        $this->db->where('orders_transkey', $order_id);
        $query = $this->db->get('order_status');

        if ($query->num_rows() != 0) {
            $last_timestamp = $query->row()->timestamp_check;

            if ($last_timestamp > $date) {
                $cached_value = $this->getCachedValue($order_id);
                switch ($cached_value) {
                    case '1':
                        return "OK";
                        break;

                    case '0':
                        return "undercut";
                        break;

                    case '2':
                        return "N/A";
                        break;
                }
            } else {
                //cache expired, query
                return
                $this->checkPrices($region_id, $type, $order_id, $station_id, $date_now, $item_id);
            }
        } else {
            return
            $this->checkPrices($region_id, $type, $order_id, $station_id, $date_now, $item_id);
        }
    }

    /**
     * Begins a series of CREST requests to check if a market order
     * is undercut or not
     * @param  int    $region_id  
     * @param  string $type       
     * @param  string $order_id   
     * @param  string $station_id 
     * @param  string $date_now   
     * @param  int    $item_id    
     * @return string             
     */
    private function checkPrices(int $region_id, string $type, string $order_id, string $station_id, string $date_now, int $item_id) : string
    {
        $this->RateLimiter->rateLimit();
        $url    = "https://crest-tq.eveonline.com/market/" . $region_id . "/orders/" . $type . "/?type=https://crest-tq.eveonline.com/inventory/types/" . $item_id . "/";
        $result = json_decode(file_get_contents($url), true);

        if ($type == 'buy') {
            $buy_status = true;
        } else if ($type == 'sell') {
            $buy_status = false;
        }

        $orderPrices = [];
        if ($station_id > 1000000000000) {
            $this->updateStatus($order_id, 2, $date_now);
            return "n/a";
        }

        for ($i = 0; $i < count($result['items']); $i++) {
            //find all orders with stationID of desired type
            if ($result['items'][$i]['location']['id_str'] == $station_id && $result['items'][$i]['buy'] == $buy_status) {
                array_push($orderPrices, $result['items'][$i]['price']);
            }
            //find the user's price based on orderID
            if ($result['items'][$i]['id_str'] == $order_id) {
                $myPrice = $result['items'][$i]['price'];
            }
        }

        //sell orders = search for lowest price, buy orders = search for highest price
        $bestPrice = 0;
        if ($buy_status && count($orderPrices) > 0) {
            $bestPrice = max($orderPrices);
        } else if (!$buy_status && count($orderPrices) > 0) {
            $bestPrice = min($orderPrices);
        }

        if (!isset($myPrice)) {
            return "expired";
        } else {
            if ($bestPrice == $myPrice) {
                $this->updateStatus($order_id, 1, $date_now);
                return "OK";
            } //order expired or fullfilled
            else {
                $this->updateStatus($order_id, 0, $date_now);
                return "undercut";
            }
        }
    }

    /**
     * Returns an order's cached result from the database
     * @param  string $order_id 
     * @return string           
     */
    private function getCachedValue(string $order_id): string
    {
        $this->db->select('status');
        $this->db->where('orders_transkey', $order_id);
        $query        = $this->db->get('order_status');
        $cached_value = $query->row()->status;

        return $cached_value;
    }

    /**
     * Updates an order's status in the database
     * @param  string $order_id 
     * @param  int    $status   
     * @param  string $date_now 
     * @return void           
     */
    private function updateStatus(string $order_id, int $status, string $date_now)
    {
        $data = array("orders_transkey" => $order_id,
            "status"                        => $status,
            "timestamp_check"               => $date_now);
        $this->db->replace('order_status', $data);
    }
}
