<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Statistics_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Chart data and configs for the trade volumes chart
     * @param  string $chars    
     * @param  int    $interval 
     * @return string json           
     */
    public function buildVolumesChart(array $configs): string
    {
        extract($configs);
        $chart = array(
            "caption"       => "Trading volumes",
            "subcaption"    => "last " . $interval . " days",
            "xAxisname"     => "days",
            "yAxisName"     => "ISK",
            "numberPrefix"  => "ISK",
            "plotFillAlpha" => "80",
            "paletteColors" => "#d9534f,#5cb85c",
            "showValues"    => "0",

        );

        $index = -1;
        $inner = "";
        $int   = $interval;
        for ($i = 1; $i < $int - 1; $i++) {
            $inner .= "SELECT " . $i . " UNION ALL ";
        }

        $fromStr = "(SELECT 0 i UNION ALL " . $inner . " SELECT " . $interval . ") i";

        $this->db->select("DATE_SUB(CURDATE(), INTERVAL i DAY) date, sum(total_buy) as sum");
        $this->db->from($fromStr);
        $this->db->join('history', 'date=DATE_SUB(CURDATE(), INTERVAL i DAY)', 'left');
        $this->db->where('history.characters_eve_idcharacters IN ' . $chars);
        $this->db->group_by('DATE_SUB(CURDATE(), INTERVAL i DAY)');
        $this->db->order_by('date', 'asc');
        $query1           = $this->db->get();
        $expenses_per_day = $query1->result_array();

        $this->db->select("DATE_SUB(CURDATE(), INTERVAL i DAY) date, sum(total_sell) as sum");
        $this->db->from($fromStr);
        $this->db->join('history', 'date=DATE_SUB(CURDATE(), INTERVAL i DAY)', 'left');
        $this->db->where('history.characters_eve_idcharacters IN ' . $chars);
        $this->db->group_by('DATE_SUB(CURDATE(), INTERVAL i DAY)');
        $this->db->order_by('date', 'asc');
        $query2           = $this->db->get();
        $revenues_per_day = $query2->result_array();

        $this->db->select('days');
        $this->db->from('calendar');
        $this->db->where('days>= (now() - INTERVAL ' . $interval . ' DAY) AND days <= DATE(NOW())');
        $this->db->order_by('days', 'asc');
        $query3 = $this->db->get();
        $dates  = $query3->result_array();
        
        //days
        $category = [];
        for ($i = 0, $max = count($dates); $i < $max; $i++) {
            array_push($category, array('label' => $dates[$i]['days']));
        }

        $categories = array(array('category' => $category));

        //expenses
        $dataExpenses = array();
        for ($i = 0, $max = count($expenses_per_day); $i < $max; $i++) {
            array_push($dataExpenses, array('value' => $expenses_per_day[$i]['sum']));
        }
        $datasetExpenses = array('seriesname' => 'Expenses', 'data' => $dataExpenses);

        //revenue
        $dataRevenues = array();
        for ($i = 0, $max = count($revenues_per_day); $i < $max; $i++) {
            array_push($dataRevenues, array('value' => $revenues_per_day[$i]['sum']));
        }

        $datasetRevenues = array('seriesname' => 'Revenues', 'data' => $dataRevenues);

        //combine
        $dataset            = array($datasetExpenses, $datasetRevenues);
        $JSON               = array();
        $JSON['chart']      = $chart;
        $JSON['categories'] = $categories;
        $JSON['dataset']    = $dataset;
        $jsonEncodedData    = json_encode($JSON, 1);

        return $jsonEncodedData;
    }


    /**
     * Gathers the problematic items for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $limit    
     * @return array             
     */
    public function getProblematicItems(string $chars, int $interval, int $limit = null): array
    {
        $this->db->select('item.eve_iditem as item_id,
                           item.name as item,
                           sum(profit.quantity_profit*profit.profit_unit) as profit,
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >=  now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN ' . $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) < 0');
        $this->db->order_by('sum(profit.quantity_profit*profit.profit_unit) ASC');
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();

        $result = $query->result_array();
        return $result;
    }

    /**
     * Generate the profits table for an interval and character set
     * @param  string $chars    [description]
     * @param  int    $interval [description]
     * @return array           [description]
     */
    public function getProfitsTable(string $chars, int $interval): array
    {
        $this->db->select('sum(total_profit) as total_profit,
                           sum(total_buy) as total_buy,
                           sum(total_sell) as total_sell,
                           avg(margin) as margin,
                           date');
        $this->db->from('history');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where('date >DATE( DATE_SUB( NOW() , INTERVAL ' . $interval . ' DAY))');
        $this->db->where('date <= curdate()');
        $this->db->group_by('date');
        $this->db->order_by('date', 'desc');
        $query1     = $this->db->get('');
        $result_day = $query1->result_array();

        $this->db->select('sum(total_profit) as total_profit,
                           sum(total_buy) as total_buy,
                           sum(total_sell) as total_sell,
                           avg(margin) as margin');
        $this->db->from('history');
        $this->db->where('characters_eve_idcharacters IN ' . $chars);
        $this->db->where('date >DATE( DATE_SUB( NOW() , INTERVAL ' . $interval . ' DAY))');
        $this->db->where('date <= curdate()');
        $this->db->order_by('date', 'desc');
        $query2 = $this->db->get('');
        $total  = $query2->result_array();

        return array("daily" => $result_day, "total" => $total);
    }

    /**
     * Gathers the best items by profit for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string       $chars    
     * @param  int          $interval 
     * @param  bool|boolean $chart    
     * @param  int|null     $limit    
     * @return array                
     */
    public function getBestItemsRaw(string $chars, int $interval, bool $chart = false, int $limit = null): array
    {
        $this->db->select('item.eve_iditem as item_id,
                           item.name as item,
                           sum(profit.quantity_profit*profit.profit_unit) as profit,
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL ' . $interval . ' day');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN ' . $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) > 0');
        $this->db->order_by('sum(profit.quantity_profit*profit.profit_unit)', 'desc');

        if ($chart) {
            $this->db->limit(20);
        }
        if ($limit) {
            $this->db->limit($limit);
        }

        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gathers the best items by margin for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $limit    
     * @return array             
     */
    public function getBestItemsMargin(string $chars, int $interval, int $limit = null): array
    {
        $this->db->select('item.eve_iditem as item_id,
                           item.name as item,
                           sum(profit.profit_unit)/sum(t1.price_unit)*100 as margin,
                           sum(profit.quantity_profit) as quantity');
        $this->db->from('profit');
        $this->db->join('transaction t1', 't1.idbuy = profit.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = profit.transaction_idbuy_sell');
        $this->db->join('characters c1', 't1.character_eve_idcharacter = c1.eve_idcharacter');
        $this->db->join('characters c2', 't2.character_eve_idcharacter = c2.eve_idcharacter');
        $this->db->join('item', 't1.item_eve_iditem = item.eve_iditem');
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('c2.eve_idcharacter IN ' . $chars);
        $this->db->where('profit.profit_unit > 0');
        $this->db->group_by('item.eve_iditem');
        $this->db->having('sum(profit.quantity_profit*profit.profit_unit) > 0');
        $this->db->order_by('sum(profit.profit_unit)/sum(t1.price_unit)', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gathers the best customers for an interval and character set, optionally
     * filtered to a maximum to use on reports. Performs API requests to find
     * out names not yet stored in the database
     * @param  string $chars    
     * @param  int    $interval 
     * @return array           
     */
    public function getBestCustomersRawProfit(string $chars, int $interval): array
    {
        $this->db->select('t2.client AS soldTo,
                           sum(profit.profit_unit * profit.quantity_profit) as profit');
        $this->db->from('profit');
        $this->db->join('transaction t1', 'profit.transaction_idbuy_buy = t1.idbuy');
        $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->group_by('t2.client');
        $this->db->order_by('sum(profit.profit_unit * profit.quantity_profit)', 'DESC');
        $this->db->limit('5');
        $query  = $this->db->get('');
        $result = $query->result_array();

        for ($i = 0; $i < count($result); $i++) {
            $this->db->where('name', $result[$i]['soldTo']);
            $query = $this->db->get('characters_public');
            $count = $query->num_rows();

            if ($count != 0) {
                $customerID = $query->row()->eve_idcharacters;
            } else {
                $url = "https://api.eveonline.com/eve/CharacterID.xml.aspx?names=" . $result[$i]['soldTo'];
                $xml = simplexml_load_file($url);

                foreach ($xml->result->rowset->row as $r) {
                    $customerID = $r['characterID'];
                }

                $data = ['eve_idcharacters' => $customerID,
                         'name'             => $result[$i]['soldTo']];

                $this->db->replace('characters_public', $data);
            }
            $result[$i]['url'] = "https://image.eveonline.com/Character/" . $customerID . "_32.jpg";
        }

        return $result;
    }

    /**
     * Gathers the best timezones by margin for an interval and character set
     * @param  string $chars    [description]
     * @param  int    $interval [description]
     * @return [type]           [description]
     */
    public function getBestTZ(string $chars, int $interval): array
    {
        $this->db->select('t2.time as time_sell,
                           (profit.quantity_profit*profit.profit_unit) as profit_total');
        $this->db->from('profit');
        $this->db->join('transaction t2', 'profit.transaction_idbuy_sell = t2.idbuy');
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->where('date(t2.time) >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->order_by('t2.time', 'asc');
        $query  = $this->db->get('');
        $result = $query->result_array();

        $EU_profit = 0;
        $AU_profit = 0;
        $US_profit = 0;

        foreach ($result as $row) {
            $time_sell = $row['time_sell'];
            $profit    = $row['profit_total'];
            $hour      = substr(substr($time_sell, -8), 0, 2);

            if ($hour > 15 && $hour < 23) {
                $EU_profit = $EU_profit + $profit;
            } else if ($hour > 7 && $hour < 15) {
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

    /**
     * Gathers the fastest turnovers for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string $chars    
     * @param  int    $interval 
     * @return array           
     */
    public function getFastestTurnovers(string $chars, int $interval): array
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
        $this->db->where('t2.time >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('t2.character_eve_idcharacter IN ' . $chars);
        $this->db->order_by('timediff(t2.time,t1.time) asc');
        $this->db->limit('5');
        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gathers the best IPH for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $limit    
     * @return array             
     */
    public function getBestIPH(string $chars, int $interval, int $limit = null): array
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
        $this->db->where('profit.characters_eve_idcharacters_OUT IN  ' . $chars);
        $this->db->group_by('item.eve_iditem');
        $this->db->having('SUM( profit.quantity_profit * profit.profit_unit ) >0');
        $this->db->order_by('iph', 'desc');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gathers the highest losses for an interval and character set, optionally
     * filtered to a maximum to use on reports
     * @param  string   $chars    
     * @param  int      $interval 
     * @param  int|null $limit    
     * @return array             
     */
    public function getMarketBlunders(string $chars, int $interval, int $limit = null): array
    {
        $this->db->select('(profit.profit_unit) / ( t1.price_unit ) AS margin,
                           item.eve_iditem AS item_id,
                           item.name as name,
                           profit.profit_unit as profit');
        $this->db->from('profit');
        $this->db->join('transaction t1', 't1.idbuy = profit.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = profit.transaction_idbuy_sell');
        $this->db->join('characters c2', 't2.character_eve_idcharacter = c2.eve_idcharacter');
        $this->db->join('item', 't1.item_eve_iditem = item.eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('c2.eve_idcharacter IN ' . $chars);
        $this->db->having('(margin < -10 AND profit < -10000000)
                           OR (margin >10 AND profit >10000000)');

        if ($limit) {
            $this->db->limit($limit);
        }
        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Gathers the best stations by profit for an interval and character set
     * @param  string $chars    
     * @param  int    $interval 
     * @return array           
     */
    public function getTopStations(string $chars, int $interval): array
    {
        $this->db->select('station.name AS station,
                           SUM( profit.quantity_profit * profit.profit_unit ) AS profit');
        $this->db->from('profit');
        $this->db->join('transaction', 'profit.transaction_idbuy_sell = transaction.idbuy');
        $this->db->join('station', 'station.eve_idstation = transaction.station_eve_idstation');
        $this->db->join('item', 'item.eve_iditem = transaction.item_eve_iditem');
        $this->db->where('profit.timestamp_sell >= now() - INTERVAL ' . $interval . ' DAY');
        $this->db->where('profit.characters_eve_idcharacters_OUT IN ' . $chars);
        $this->db->group_by('station.name');
        $this->db->having('SUM( profit.quantity_profit * profit.profit_unit ) >0');
        $this->db->order_by('SUM( profit.quantity_profit * profit.profit_unit ) DESC ');
        $this->db->limit('5');
        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }

    /**
     * Generates the data and configs required to build the item distribution chart
     * @param  string $chars    
     * @param  int    $interval 
     * @return string json           
     */
    public function buildDistributionChart(array $configs): string
    {
        extract($configs);
        $arrData["chart"] = array(
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
            "showLegend"                => "0",
            "useDataPlotColorForLabels" => "1");

        $arrData["data"] = array();

        $item_names  = [];
        $item_values = [];

        $data = $this->getBestItemsRaw($chars, $interval, true);

        foreach ($data as $key => $value) {
            array_push($item_names, $value['item']);
            array_push($item_values, $value['profit']);
        }

        for ($i = 0; $i < count($item_names); $i++) {
            array_push($arrData["data"], array("label" => (string) $item_names[$i],
                "value"                                    => (string) $item_values[$i]));
        }
        $arrData["chart"];
        $jsonEncodedData = json_encode($arrData);

        return $jsonEncodedData;
    }

    /**
     * Gets the lifetime stats for a set of characters, optionally
     * filtered by transaction type
     * @param  string      $chars 
     * @param  string|null $type  
     * @return stdClass             
     */
    public function getTotalTransactions(string $chars, string $type = null) : stdClass
    {
        $this->db->select('count(idbuy) as total');
        $this->db->from('transaction');
        if (!empty($type)) {
            $this->db->where('transaction_type', $type);
        }
        $this->db->where('character_eve_idcharacter IN ' . $chars);
        $query = $this->db->get('');

        $result = $query->row();
        return $result;
    }

    /**
     * Gets the lifetime sum of transactions for a set of characters, optionally
     * filtered by transaction type
     * @param  string      $chars 
     * @param  string|null $type  
     * @return stdClass             
     */
    public function getSumTransactions(string $chars, string $type = null) : stdClass
    {
        $this->db->select('sum(price_unit * quantity) as total');
        $this->db->from('transaction');
        if (!empty($type)) {
            $this->db->where('transaction_type', $type);
        }
        $this->db->where('character_eve_idcharacter IN ' . $chars);
        $query = $this->db->get('');

        $result = $query->row();
        return $result;
    }

    /**
     * Gets the lifetime sum of profits for a set of characters, optionally
     * filtered by transaction type
     * @param  string $chars 
     * @return stdClass        
     */
    public function getTotalProfit(string $chars) : stdClass
    {
        $this->db->select('sum(profit_unit * quantity_profit) as sum');
        $this->db->from('profit');
        $this->db->where('characters_eve_idcharacters_OUT IN ' . $chars);
        $query = $this->db->get('');

        $result = $query->row();
        return $result;
    }

    /**
     * Returns the user's signup date
     * @param  string $iduser 
     * @return stdClass         
     */
    public function getSignupDate(string $iduser) : stdClass
    {
        $this->db->select('registration_date');
        $this->db->from('user');
        $this->db->where('iduser', $iduser);
        $query = $this->db->get('');

        $result = $query->row();
        return $result;
    }

    /**
     * Get highest day and value for a given metric for a set of
     * characters
     * @param  string $chars  
     * @param  string $metric 
     * @return stdClass         
     */
    public function getHighestMetric(string $chars, string $metric) : stdClass
    {
        $this->db->select('date, sum(' . $metric . ') as max');
        $this->db->from('history');
        $this->db->where('characters_eve_idcharacters IN ' .  $chars);
        $this->db->group_by('date');
        $this->db->order_by('max', 'desc');
        $this->db->limit(1);
        $query = $this->db->get('');

        $result = $query->row();
        return $result;
    }
}
