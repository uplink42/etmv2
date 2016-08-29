<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPieData($character_id)
    {
        $this->db->where('eve_idcharacter', $character_id);
        $query  = $this->db->get('characters');
        $result = $query->row();

        $arrData["chart"] = array(
            "paletteColors"             => "#f6a821,#f45b00,#8e0000,#007F00,#1aaf5d",
            "bgColor"                   => "#44464f",

            "showBorder"=> "0",
            "use3DLighting"=> "0",
            "showShadow"=> "0",
            "enableSmartLabels"=> "0",
            "startingAngle"=> "0",
            "showPercentValues"=> "1",
            "showPercentInTooltip"=> "0",
            "decimals"=> "1",
            "captionFontSize"=> "0",
            "subcaptionFontSize"=> "0",
            "subcaptionFontBold"=> "0",
            "toolTipColor"=> "#000000",
            "toolTipBorderThickness"=> "0",
            "toolTipBgColor"=> "#ffffff",
            "toolTipBgAlpha"=> "80",
            "toolTipBorderRadius"=> "2",
            "toolTipPadding"=> "5",
            "showHoverEffect"=> "1",
            "showLegend"=> "1",
            "legendBgColor"=> "#ffffff",
            "legendBorderAlpha"=> "0",
            "legendShadow"=> "0",
            "legendItemFontSize"=> "12",
            "legendItemFontColor"=> "#666666",
             "labelfontsize" => "0",
            "useDataPlotColorForLabels"=> "1" );

        $arrData["data"] = array();
        $assetTypes      = array("wallet", "assets", "escrow", "sellorders");
        $assetValues     = array($result->balance, $result->networth, $result->escrow, $result->total_sell);

        for ($i = 0; $i < count($assetTypes); $i++) {
            array_push($arrData["data"], array("label" => (string) $assetTypes[$i],
                "value"                                    => (string) $assetValues[$i]));
        }

        $arrData["chart"];
        $jsonEncodedData = json_encode($arrData);

        return $jsonEncodedData;
    }

    public function getWeekProfits($character_id)
    {
        $this->db->select('total_profit');
        $this->db->where('characters_eve_idcharacters', $character_id);
        $this->db->where("date>= (now() - INTERVAL 7 DAY)");
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('history');

        /*$query = $this->db->query("SELECT total_profit FROM history
        WHERE characters_eve_idcharacters = '$character_id'
        AND date >= now() - INTERVAL 7 DAY");*/
        $result = $query->result_array();

        $data = "[";
        for ($i = 0; $i <= count($result) - 1; $i++) {
            $data .= $result[$i]['total_profit'];
            if ($i < count($result) - 1) {
                $data .= ",";
            }
        }
        $data .= "]";
        return $data;
    }

    public function getTotalProfitsTrends($character_id)
    {
        $this->db->select('coalesce(sum(total_profit),0) as sum');
        $this->db->where('characters_eve_idcharacters', $character_id);
        $this->db->where("date>= (now() - INTERVAL 7 DAY)");
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('history');

        $result                  = $query->row()->sum;
        $result == 0 ? $week_avg = 0 : $week_avg = $result / 7;

        $this->db->select('coalesce(total_profit,0) as sum');
        $this->db->where('characters_eve_idcharacters', $character_id);
        $this->db->where("date>= (now() - INTERVAL 24 HOUR)");
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('history');
        log_message('error', $this->db->last_query());
        $today_profit = $query->row()->sum;

        $week_avg == 0 ? $trend = 0 : $trend = $today_profit / $week_avg * 100;

        $data = ["total_week" => $result, "avg_week" => $week_avg, "trend_today" => $trend];

        return $data;
    }

    public function getNewInfo($character_id)
    {
        $this->db->where('characters_eve_idcharacters', $character_id);
        $query         = $this->db->get('new_info');
        return $result = $query->row();
    }

    public function getProfits($character_id)
    //redo this query, profit data
    {
        $this->db->select('p.profit_unit as profit_unit,
                        p.quantity_profit as quantity,
                        p.timestamp_sell as sell_time,
                        i.name as item_name,
                        i.eve_iditem as item_id,
                        sys2.name as system_name,
                        s2.eve_idstation as station_from,
                        s1.eve_idstation as station_to,
                        c1.eve_idcharacter as character_from,
                        c2.eve_idcharacter as character_to,
                        t1.price_unit as price_buy,
                        t2.price_unit as price_sell');
        $this->db->from('profit p');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell');
        $this->db->join('item i', 'i.eve_iditem = t2.item_eve_iditem');
        $this->db->join('station s1', 's1.eve_idstation = t1.station_eve_idstation');
        $this->db->join('station s2', 's2.eve_idstation = t2.station_eve_idstation');
        $this->db->join('system sys2', 'sys2.eve_idsystem = s2.system_eve_idsystem');
        $this->db->join('characters c1', 'c1.eve_idcharacter = t1.character_eve_idcharacter');
        $this->db->join('characters c2', 'c2.eve_idcharacter = t2.character_eve_idcharacter');
        $this->db->where('t2.character_eve_idcharacter', $character_id);
        $this->db->where("t2.time>= (now() - INTERVAL 30 DAY)");
        $this->db->order_by('t2.time', 'desc');
        $query  = $this->db->get();
        log_message('error', $this->db->last_query());
        $result = $query->result_array();


        for ($i = 0; $i <= count($result)-1; $i++) {
            $price_buy     = $result[$i]['price_buy'];
            $profit_unit   = $result[$i]['profit_unit'];
            $character_buy = $result[$i]['character_from'];
            $character_sell = $result[$i]['character_to'];
            $station_from = $result[$i]['station_from'];
            $station_to   = $result[$i]['station_to'];

            $CI = &get_instance();
            $CI->load->model('Tax_Model');
            $CI->Tax_Model->tax($station_from, $station_to, $character_buy, $character_sell, "buy", "sell");
            $transTaxFrom  = $CI->Tax_Model->calculateTaxFrom();
            $brokerFeeFrom = $CI->Tax_Model->calculateBrokerFrom();

            $price_buy            = $price_buy * $transTaxFrom * $brokerFeeFrom;
            $result[$i]['margin'] = $profit_unit / $price_buy * 100;
            $result[$i]['profit_total'] = $profit_unit * $result[$i]['quantity'];
            $result[$i]['url'] = "https://image.eveonline.com/Type/".$result[$i]['item_id']."_32.png";
        }

        return $result;
    }
}
