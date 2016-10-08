<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MarketOrders_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMarketOrders($chars, $type, $check = false)
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
                $result[$i]['status'] = $this->checkOrder($result[$i]['order_id'],
                    $result[$i]['station_id'],
                    $result[$i]['region_id'],
                    $type,
                    $result[$i]['item_id']);
            }
        }
        return $result;
    }

    public function checkOrder($order_id, $station_id, $region_id, $type, $item_id)
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

        //die($query->row());
        if ($query->num_rows() > 1) {
            $last_timestamp = $query->row()->timestamp_check;

            if ($last_timestamp > $date) {
                $cached_value = $this->getCachedValue($order_id);

                //value is cached
                if ($cached_value) {
                    return "OK";
                } else {
                    return "undercut";
                }
            }
        } else {
            $this->load->model('common/RateLimiter');
            $this->RateLimiter->rateLimit();
            //get crest data
            $url    = "https://crest-tq.eveonline.com/market/" . $region_id . "/orders/" . $type . "/?type=https://crest-tq.eveonline.com/inventory/types/" . $item_id . "/";
            $result = json_decode(file_get_contents($url), true);

            if ($type == 'buy') {
                $buy_status = true;
            } else if ($type == 'sell') {
                $buy_status = false;
            }

            $orderPrices = [];

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
            if ($buy_status) {
                $bestPrice = max($orderPrices);
            } else if (!$buy_status) {
                $bestPrice = min($orderPrices);
            }

            if (!isset($myPrice)) {
                return "expired";
            } else {

                if ($bestPrice == $myPrice) {
                    $this->updateStatus($order_id, 1, $date_now);
                    return "OK";
                }   //order expired or fullfilled
                     else {
                        $this->updateStatus($order_id, 0, $date_now);
                        return "undercut";
                    }
                }
        }
    }

    private function getCachedValue()
    {
        $this->db->select('status');
        $this->db->where('orders_transkey', $order_id);
        $query        = $this->db->get('order_status');
        $cached_value = $query->row()->status;

        return $cached_value;
    }

    private function updateStatus($order_id, $status, $date_now)
    {
        $data = array("orders_transkey" => $order_id,
            "status"                        => "1",
            "timestamp_check"               => $date_now);
        $this->db->replace('order_status', $data);
    }

}
