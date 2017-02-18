<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the required data to build the networth distribution pie chart
     * @param  string $chars 
     * @return [json]        
     */
    public function getPieData(string $chars): string
    {
        $this->db->select('sum(networth) as networth, sum(escrow) as escrow, sum(total_sell) as total_sell, sum(balance) as balance');
        $this->db->where('eve_idcharacter IN ' . $chars);
        $query  = $this->db->get('characters');
        $result = $query->row();

        $arrData["chart"] = array(
            "paletteColors"             => "#f6a821,#f45b00,#8e0000,#007F00,#1aaf5d",
            "bgColor"                   => "#44464f",
            "showBorder"                => "0",
            "use3DLighting"             => "0",
            "showShadow"                => "0",
            "enableSmartLabels"         => "0",
            "startingAngle"             => "0",
            "showPercentValues"         => "1",
            "showPercentInTooltip"      => "0",
            "decimals"                  => "1",
            "captionFontSize"           => "0",
            "subcaptionFontSize"        => "0",
            "subcaptionFontBold"        => "0",
            "toolTipColor"              => "#000000",
            "toolTipBorderThickness"    => "0",
            "toolTipBgColor"            => "#ffffff",
            "toolTipBgAlpha"            => "80",
            "toolTipBorderRadius"       => "2",
            "toolTipPadding"            => "5",
            "showHoverEffect"           => "1",
            "showLegend"                => "1",
            "legendBgColor"             => "#ffffff",
            "legendBorderAlpha"         => "0",
            "legendShadow"              => "0",
            "legendItemFontSize"        => "12",
            "legendItemFontColor"       => "#666666",
            "labelfontsize"             => "0",
            "useDataPlotColorForLabels" => "1");

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

    /**
     * Return the list of weekly profits for the sparkline
     * @param  string $chars 
     * @return [string]        
     */
    public function getWeekProfits(string $chars): string
    {
        $this->db->select('total_profit');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where("date>= (now() - INTERVAL 7 DAY)");
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('history');
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

    /**
     * Returns the trend line for a set of characters
     * @param  string $chars 
     * @return [array]        
     */
    public function getTotalProfitsTrends(string $chars): array
    {

        $this->db->select('coalesce(sum(total_profit),0) as sum');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where("date>= (now() - INTERVAL 7 DAY)");
        $this->db->order_by('date', 'asc');
        $query1 = $this->db->get('history');

        $this->db->select('coalesce(total_profit,0) as sum');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where("date>= (now() - INTERVAL 24 HOUR)");
        $this->db->order_by('date', 'asc');
        $query2 = $this->db->get('history');

        $result                  = $query1->row()->sum;
        $result == 0 ? $week_avg = 0 : $week_avg = $result / 7;

        $today_profit           = $query2->row()->sum;
        $week_avg == 0 ? $trend = 0 : $trend = $today_profit / $week_avg * 100;
        $data                   = ["total_week" => $result, "avg_week" => $week_avg, "trend_today" => $trend];
        return $data;
    }

    /**
     * Returns the number of new contracts, transactions, etc on the main page
     * Todo: re-do for several characters
     * @param  string $chars 
     * @return [stdClass]        
     */
    public function getNewInfo(string $chars): stdClass
    {
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $query         = $this->db->get('new_info');
        return $result = $query->row();
    }

    /**
     * Returns a short scenario with the latest profits for a
     * set of characters and a specified interval
     * @param  int|integer $interval 
     * @param  string|null $chars    
     * @return [array]                
     */
    public function getProfits(int $interval = 1, string $chars = null): array
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
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->where("t2.time>= (now() - INTERVAL " . $interval . " DAY)");
        $this->db->order_by('t2.time', 'desc');
        $this->db->limit(5000);
        $query = $this->db->get();
        $count = $query->num_rows();

        $result = $query->result_array();

        for ($i = 0; $i <= count($result) - 1; $i++) {
            $price_buy      = $result[$i]['price_buy'];
            $profit_unit    = $result[$i]['profit_unit'];
            $character_buy  = $result[$i]['character_from'];
            $character_sell = $result[$i]['character_to'];
            $station_from   = $result[$i]['station_from'];
            $station_to     = $result[$i]['station_to'];

            $CI = &get_instance();
            $CI->load->model('Tax_Model');
            $CI->Tax_Model->tax($station_from, $station_to, $character_buy, $character_sell, "buy", "sell");
            $transTaxFrom  = $CI->Tax_Model->calculateTaxFrom();
            $brokerFeeFrom = $CI->Tax_Model->calculateBrokerFrom();

            $price_buy                  = $price_buy * $transTaxFrom * $brokerFeeFrom;
            $result[$i]['margin']       = $profit_unit / $price_buy * 100;
            $result[$i]['profit_total'] = $profit_unit * $result[$i]['quantity'];
            $result[$i]['url']          = "https://image.eveonline.com/Type/" . $result[$i]['item_id'] . "_32.png";
        }

        $data = array("result" => $result, "count" => $count);
        return $data;
    }
}
