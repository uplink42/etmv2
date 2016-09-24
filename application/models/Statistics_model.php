<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Statistics_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function buildVolumesChart($chars, $interval)
    {
    	$chart = array(
		    "caption" => "Trading volumes",
		    "subcaption" => "last ". $interval. " days",
		    "xAxisname" => "days",
		    "yAxisName"=> "ISK",
		    "numberPrefix" => "ISK",
		    "plotFillAlpha"=> "80",
		    "paletteColors"=> "#d9534f,#5cb85c",
		    
          );

    	$index = -1;

    	$inner = "";
        $int = $interval;
        for($i=1; $i<$int-1; $i++) {
            $inner .= "SELECT ". $i . " UNION ALL ";
        }

        $fromStr = "(SELECT 0 i UNION ALL " . $inner . " SELECT " . $interval . ") i";

        $this->db->select("DATE_SUB(CURDATE(), INTERVAL i DAY) date, sum(total_buy) as sum");
        $this->db->from($fromStr);
        $this->db->join('history', 'date=DATE_SUB(CURDATE(), INTERVAL i DAY)', 'left');
        $this->db->where('history.characters_eve_idcharacters IN '. $chars);
        $this->db->group_by('DATE_SUB(CURDATE(), INTERVAL i DAY)');
        $this->db->order_by('date', 'asc');
        $query1 = $this->db->get();
        $expenses_per_day = $query1->result_array();
        log_message('error', $this->db->last_query());


        $this->db->select("DATE_SUB(CURDATE(), INTERVAL i DAY) date, sum(total_sell) as sum");
        $this->db->from($fromStr);
        $this->db->join('history', 'date=DATE_SUB(CURDATE(), INTERVAL i DAY)', 'left');
        $this->db->where('history.characters_eve_idcharacters IN '. $chars);
        $this->db->group_by('DATE_SUB(CURDATE(), INTERVAL i DAY)');
        $this->db->order_by('date', 'asc');
        $query2 = $this->db->get();

        log_message('error', $this->db->last_query());
        $revenues_per_day = $query2->result_array();

        $this->db->select('days');
        $this->db->from('calendar');
        $this->db->where('days>= (now() - INTERVAL '. $interval .' DAY) AND days <= DATE(NOW())');
        $this->db->order_by('days', 'asc');
        $query3 = $this->db->get();
        $dates = $query3->result_array();
        log_message('error', $this->db->last_query());
        //days
        $category = [];
        
        for($i=0, $max=count($dates); $i<$max; $i++) {
            array_push($category, array('label' => $dates[$i]['days']));
        }
        
        $categories = array(array('category' => $category));
        
        //expenses
        $dataExpenses = array();
        for($i=0, $max=count($expenses_per_day); $i<$max; $i++) {
            array_push($dataExpenses, array('value' => $expenses_per_day[$i]['sum']));
        }

        $datasetExpenses = array('seriesname' => 'Expenses', 'data' => $dataExpenses);

        //revenue
        $dataRevenues = array();
        for($i=0, $max=count($revenues_per_day); $i<$max; $i++) {
            array_push($dataRevenues, array('value' => $revenues_per_day[$i]['sum']));
        }

        $datasetRevenues = array('seriesname' => 'Revenues', 'data' => $dataRevenues);


        //combine
        $dataset = array($datasetExpenses, $datasetRevenues);
        $JSON = array();
        $JSON['chart'] = $chart;
        $JSON['categories'] = $categories;
        $JSON['dataset'] = $dataset;
        $jsonEncodedData = json_encode($JSON, true);

        return $jsonEncodedData;
    }

    public function getProblematicItems($chars, $interval)
    {
        $this->db->select('item.eve_iditem as item_id, 
                           item.name as item, 
                           sum(profit.quantity_profit*profit.profit_unit) as profit, 
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >=  now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN '. $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) < 0');
        $this->db->order_by('sum(profit.quantity_profit*profit.profit_unit) ASC');
        $query = $this->db->get();
        
        $result = $query->result();
        return $result;
    }

    public function getProfitsTable($chars, $interval)
    {
        $this->db->select('total_profit, total_buy, total_sell, margin');
        $this->db->from('history');
        $this->db->where('characters_eve_idcharacters IN ' . $chars );
        $this->db->where('date >DATE( DATE_SUB( NOW() , INTERVAL '. $interval . ' DAY))');
        $this->db->where('date <= curdate()');
        $this->db->group_by('date');
        $this->db->order_by('date', 'desc');
        $query1 = $this->db->get('');
        $result_day = $query1->result_array();

        $this->db->select('sum(total_profit), sum(total_buy), sum(total_sell), avg(margin)');
        $this->db->from('history');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where('date >DATE( DATE_SUB( NOW() , INTERVAL '. $interval . ' DAY))');
        $this->db->where('date <= curdate()');
        $this->db->group_by('date');
        $this->db->order_by('date', 'desc');
        $query2 = $this->db->get('');
        $total = $query2->result_array();

        return array("daily" => $result_day, "total" => $total);
    }

    public function getBestItemsRaw($chars, $interval)
    {
        $this->db->select('item.eve_iditem as item_id, 
                           item.name as item, 
                           sum(profit.quantity_profit*profit.profit_unit) as profit, 
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL ' . $interval .' day');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN ' . $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) > 0');
        $this->db->order_by('sum(profit.quantity_profit*profit.profit_unit)', 'desc');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getBestItemsMargin($chars, $interval)
    {
        $this->db->select('item.eve_iditem as item_id,
                           item.name as item,
                           sum(profit.profit_unit)/sum(t1.price_unit) as margin, 
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction t1', 't1.idbuy = profit.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = profit.transaction_idbuy_sell');
        $this->db->join('characters c1', 't1.character_eve_idcharacter = c1.eve_idcharacter');
        $this->db->join('characters c2', 't2.character_eve_idcharacter = c2.eve_idcharacter');
        $this->db->join('item', 't1.item_eve_iditem = item.eve_iditem');
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval . ' HOUR');
        $this->db->where('c2.eve_idcharacter IN ' . $chars);
        $this->db->where('profit.profit_unit > 0');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) > 0');
        $this->db->order_by('sum(profit.profit_unit)/sum(t1.price_unit)', 'DESC');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getBestCustomersRawProfit($chars, $interval)
    {
        $this->db->select('t2.client AS soldTo, 
                           sum(profit.profit_unit * profit.quantity_profit) as profit');
        $this->db->from('profit');
        $this->db->join('transaction t1', 'profit.transaction_idbuy_buy = t1.idbuy');
        $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
        $this->db->where('t2.time >= now() - INTERVAL '. $interval . ' HOUR');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->group_by('t2.client');
        $this->db->order_by('sum(profit.profit_unit * profit.quantity_profit)', 'DESC');
        $this->db->limit('5');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getBestTZ($chars, $interval)
    {
        $this->db->select('t2.time as time_sell, 
                           (profit.quantity_profit*profit.profit_unit) as profit_total');
        $this->db->from('profit');
        $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval  . ' DAY');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->where('date(t2.time) >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->order_by('t2.time', 'asc');
        $query = $this->db->get('');
        $result = $query->result_array();

        $EU_profit = 0;
        $AU_profit = 0;
        $US_profit = 0;

        foreach($result as $row) {
            $time_sell = $row['time_sell'];
            $profit = $row['profit_total'];
            $hour = substr(substr($time_sell, -8), 0, 2);

            if($hour>15 && $hour<23) {
                $EU_profit = $EU_profit + $profit;
            } else if ($hour>7 && $hour<15) {
                $AU_profit = $AU_profit + $profit;
            } else {
                $US_profit = $US_profit + $profit;
            }
        }

        $tz_profits = array(
            "eu" => $EU_profit,
            "us" => $US_profit,
            "au" => $AU_profit);
        arsort($tz_profits);
        
        return $tz_profits;
    }

    public function getFastestTurnovers($chars, $interval)
    {
        $this->db->select('item.name as item, 
                            timediff(t2.time,t1.time) as difference,
                            (quantity_profit*profit_unit) as total, 
                            item.eve_iditem as item_id');
        $this->db->from('profit');
        $this->db->join('transaction t1', 'profit.transaction_idbuy_buy = t1.idbuy');
        $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
        $this->db->join('characters', 't2.character_eve_idcharacter = characters.eve_idcharacter');
        $this->db->join('item', 't2.item_eve_iditem = item.eve_iditem');
        $this->db->where('t2.time >= now() - INTERVAL '. $interval . ' DAY');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->order_by('timediff(t2.time,t1.time) asc');
        $this->db->limit('5');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getBestIPH($chars, $interval)
    {
        $this->db->select('item.name AS item, 
                           item.eve_iditem AS item_id,
                           SUM( profit.quantity_profit * profit.profit_unit ) AS profit,
                           SUM( profit.quantity_profit ) AS quantity, 
                           (SUM( profit.quantity_profit * profit.profit_unit )) / AVG( TIME_TO_SEC( TIMEDIFF( profit.timestamp_sell, profit.timestamp_buy ) ) /3600 ) AS iph');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN  '. $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('SUM( profit.quantity_profit * profit.profit_unit ) >0');
        $this->db->order_by('iph', 'desc');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getMarketBlunders($chars, $interval)
    {
        $this->db->select('(profit.profit_unit) / ( t1.price_unit ) AS margin,
                           item.eve_iditem AS item_id, 
                           item.name as name, profit.profit_unit as profit');
        $this->db->from('profit');
        $this->db->join('transaction t1', 't1.idbuy = profit.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = profit.transaction_idbuy_sell');
        $this->db->join('characters c2', 't2.character_eve_idcharacter = c2.eve_idcharacter');
        $this->db->join('item', 't1.item_eve_iditem = item.eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL '. $interval .' DAY');
        $this->db->where('c2.eve_idcharacter IN '. $chars);
        $this->db->having('(margin < -10 AND profit < -10000000)
                           OR (margin >10 AND profit >10000000)');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    public function getTopStations($chars, $interval)
    {
        $this->db->select('station.name AS station, 
                           SUM( profit.quantity_profit * profit.profit_unit ) AS profit');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('station', 'station.eve_idstation = transaction.station_eve_idstation');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL '. $chars . ' DAY');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN ' . $chars);
        $this->db->group_by('station.name');
        $this->db->having('SUM( profit.quantity_profit * profit.profit_unit ) >0');
        $this->db->order_by('SUM( profit.quantity_profit * profit.profit_unit ) DESC ');
        $this->db->limit('5');
        $query = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }
}
