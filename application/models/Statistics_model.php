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
		    "baseFontColor"=> "#333333",
		    "baseFont"=> "Helvetica Neue,Arial",
		    "captionFontSize"=> "14",
		    "subcaptionFontSize"=> "14",
		    "subcaptionFontBold"=> "0",
		    "showBorder"=> "0",
		    "showShadow"=> "0",
		    "canvasBgColor"=> "#ffffff",
		    "canvasBorderAlpha"=> "0",
		    "divlineAlpha"=> "100",
		    "divlineColor"=> "#999999",
		    "divlineThickness"=> "1",
		    "divLineDashed"=> "1",
		    "divLineDashLen"=> "1",
		    "usePlotGradientColor"=> "0",
		    "showplotborder"=> "0",
		    "valueFontColor"=> "#ffffff",
		    "placeValuesInside"=> "1",
		    "showHoverEffect"=> "1",
		    "rotateValues"=> "1",
		    "showXAxisLine"=> "1",
		    "xAxisLineThickness"=> "1",
		    "xAxisLineColor"=> "#999999",
		    "showAlternateHGridColor"=> "0",
		    "legendBgAlpha"=> "0",
		    "legendBorderAlpha"=> "0",
		    "legendShadow"=> "0",
		    "legendItemFontSize"=> "10",
		    "legendItemFontColor"=> "#666666"	    
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

   
    

}
