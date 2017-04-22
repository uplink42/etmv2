<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profits_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Datatables', 'dt');
    }

    /**
     * Returns a list of profits for the specified interval
     * and character set, optionally filtered by item
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $item_id  
     * @return string json          
     */
    public function getProfits(array $configs): string
    {
        extract ($configs);
        $this->load->model('common/User');
        $profit_settings = $this->User->getUserProfitSettings($user_id);

        $this->db->start_cache();
        $this->db->select("p.profit_unit as profit_unit,
            p.transaction_idbuy_buy as idbuy,
            p.transaction_idbuy_sell as idsell,
            i.eve_iditem as item_id,
            i.name AS item_name,
            sys1.name AS sys_buy,
            sys2.name as sys_sell,
            s1.eve_idstation as station_buy_id,
            s2.eve_idstation as station_sell_id,
            t1.time as time_buy,
            t2.time as time_sell,
            t1.idbuy as trans_buy,
            t2.idbuy as trans_sell,
            t1.price_unit as buy_price,
            t2.price_unit as sell_price,
            (t1.price_unit * p.quantity_profit) as buy_price_total,
            (t2.price_unit * p.quantity_profit) as sell_price_total,
            t1.quantity as quantity_buy,
            t2.quantity as quantity_sell,
            p.profit_unit as profit_unit,
            p.quantity_profit AS profit_quantity,
            c1.name as character_buy,
            c2.name as character_sell,
            c1.eve_idcharacter as char_buy_id,
            c2.eve_idcharacter as char_sell_id,
            coalesce(sys1.name, 'Unknown Citadel') as sys_from,
            coalesce(sys2.name, 'Unknown Citadel') as sys_to,
            (p.quantity_profit * p.profit_unit) as profit_total,
            time_to_sec(timediff(t2.time,t1.time))/60 as diff");
        $this->db->from('profit p');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell');
        $this->db->join('station s1', 't1.station_eve_idstation = s1.eve_idstation', 'left');
        $this->db->join('station s2', 't2.station_eve_idstation = s2.eve_idstation', 'left');
        $this->db->join('system sys1', 'sys1.eve_idsystem = s1.system_eve_idsystem', 'left');
        $this->db->join('system sys2', 'sys2.eve_idsystem = s2.system_eve_idsystem', 'left');
        $this->db->join('characters c1', 't1.character_eve_idcharacter = c1.eve_idcharacter');
        $this->db->join('characters c2', 't2.character_eve_idcharacter = c2.eve_idcharacter');
        $this->db->join('item i', 't1.item_eve_iditem = i.eve_iditem', 'left');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $chars);
        
        if (isset($item_id)) {
            $this->db->where('i.eve_iditem', $item_id);
        }

        $this->db->where('p.timestamp_sell>= now() - INTERVAL ' . $interval . ' DAY');
        
        if (!isset($defs['order'][0])) {
            $this->db->order_by('t2.time', 'desc');
        }

        $result = $this->dt->generate($defs, 'i.name', 'profit_total');
        $count  = count($result['data']);

        for ($i = 0; $i < $count; $i++) {
            $diff           = (float) $result['data'][$i]->diff;
            $price_buy      = (float) $result['data'][$i]->buy_price;
            $profit_unit    = (float) $result['data'][$i]->profit_unit;
            $character_buy  = $result['data'][$i]->char_buy_id;
            $character_sell = $result['data'][$i]->char_sell_id;
            $station_from   = (string) $result['data'][$i]->station_buy_id;
            $station_to     = (string) $result['data'][$i]->station_sell_id;

            if ($result['data'][$i]->diff < 60) {
                $result['data'][$i]->diff = number_format($diff, 1) . " m";
            } else if ($result['data'][$i]->diff < 1440) {
                $result['data'][$i]->diff = number_format($diff / 60, 1) . " h";
            } else {
                $result['data'][$i]->diff = number_format($diff / 1440, 1) . " d";
            }

            $CI = &get_instance();
            $CI->load->model('Tax_Model');
            $CI->Tax_Model->tax($station_from, $station_to, $character_buy, $character_sell, $profit_settings);
            $transTaxFrom  = $CI->Tax_Model->calculateTax('from');
            $brokerFeeFrom = $CI->Tax_Model->calculateBroker('from');

            $price_buy                        = $price_buy * $transTaxFrom * $brokerFeeFrom;
            $result['data'][$i]->margin       = $profit_unit / $price_buy * 100;
        }
        
        $data   = json_encode(['data'            => injectIcons($result['data'], true), 
                               'draw'            => (int)$result['draw'], 
                               'recordsTotal'    => $result['max'],
                               'recordsFiltered' => $result['max'],
                               'recordsSum'      => $result['sum']]);
        return $data;
    }

    /**
     * Gathers the profit chart data object
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $item_id  
     * @return string json             
     */
    public function getProfitChartData(array $configs): string
    {
        extract ($configs);
        $arrData = array(
            "chart" => array(
                "caption"       => "Profit evolution",
                "subCaption"    => "last " . $interval . " days",
                "xAxisName"     => "Day",
                "yAxisName"     => "Profit (ISK)",
                "paletteColors" => "#0075c2,#1aaf5d,#f2c500",
                "drawCrossLine" => "1",
                "crossLineColor" => "#f6a821",
                "crossLineAlpha" => "100",
                "tooltipGrayOutColor" => "#80bfff",
                "canvasBgAlpha" => "0",
                "bgColor" => "#32353d",
                "bgAlpha" => "100",
                "outCnvBaseFontColor" => "#fff",
                "showAlternateHGridColor" => "0",
                "captionFontColor" =>"#fff",
                "anchorAlpha" => '0',
                "labelFontColor" => "#fff",
                "showValues" => "0",
                "numberSuffix" => " ISK",
                "showBorder" => "0"
            ),
        );

        $index = -1;
        $inner = "";
        $int   = $interval;
        for ($i = 1; $i < $int - 1; $i++) {
            $inner .= "SELECT " . $i . " UNION ALL ";
        }

        $fromStr = "(SELECT 0 i UNION ALL " . $inner . " SELECT " . $interval . ") i";
        $this->db->select("DATE_SUB(CURDATE(), INTERVAL i DAY) date, sum(total_profit) as sum");
        $this->db->from($fromStr);
        $this->db->join('history', 'date=DATE_SUB(CURDATE(), INTERVAL i DAY)', 'left');
        $this->db->where('history.characters_eve_idcharacters IN ' . $chars);
        $this->db->group_by('DATE_SUB(CURDATE(), INTERVAL i DAY)');
        $query  = $this->db->get();
        $result = $query->result();

        $profits_list    = array();
        $days_list       = array();
        $arrData['data'] = array();

        foreach ($result as $row) {
            $index++;
            array_push($days_list, $row->date);
            array_push($profits_list, $row->sum);
            array_push($arrData['data'], array("label" => (string) $days_list[$index], "value" => (string) $profits_list[$index]));
        }

        $jsonEncodedData = json_encode($arrData);
        return $jsonEncodedData;
    }
}
