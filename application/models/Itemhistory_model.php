<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemhistory_model extends CI_Model
{
    private $chars;
    private $item_id;
    private $interval;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Datatables', 'dt');
    }

    public function getStatsByInterval(array $configs)
    {
        extract($configs);
        $this->chars    = $chars;
        $this->item_id  = $item_id;
        $this->interval = $interval;

        $result         = [];
        $this->interval = 150;

        // get today total sell
        $result['sell']   = $this->getSoldStats();
        $result['buy']    = $this->getBoughtStats();
        $result['profit'] = $this->getProfitStats();

        return json_encode($result);
    }

    private function getSoldStats()
    {
        $data             = [];
        $data['snapshot'] = [];

        // totals
        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
    		               COALESCE(sum(t.quantity), 0) as quantity');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Sell');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
    		               COALESCE(sum(t.quantity), 0) as quantity');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Sell');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL ' . $this->interval . ' DAY');
        $query                        = $this->db->get('');
        $data['snapshot']['interval'] = $query->row();

        // daily stats
        $salesByDay = [];
        $date       = new DateTime();
        for ($i = 0; $i < $this->interval; $i++) {
            $previous         = $date->sub(new DateInterval('P1D'));
            $str              = $previous->format('Y-m-d');
            $salesByDay[$str] = ['sales' => 0, 'quantity' => 0];
        }

        $this->db->select('COALESCE(sum(t.price_total), 0) as sales,
    					   COALESCE(sum(t.quantity), 0) as quantity,
    		               c.days as day');
        $this->db->from('calendar c');
        $this->db->join('transaction t', 'DATE(t.time) = c.days', 'left');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.time>= now() - INTERVAL ' . $this->interval . ' DAY');
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.transaction_type', 'Sell');
        $this->db->group_by('c.days');
        $query  = $this->db->get('');
        $result = $query->result();

        foreach ($result as $day) {
            $salesByDay[$day->day] = ['sales' => $day->sales, 2, 'quantity' => $day->quantity, 0];
        }

        $sales_list    = array();
        $days_list     = array();
        $quantity_list = array();

        foreach ($salesByDay as $key => $value) {
            array_push($days_list, ['label' => $key]);
            array_push($sales_list, ['value' => $value['sales']]);
            array_push($quantity_list, ['value' => $value['quantity']]);
        }

        // build chart
        $obj = [
            'chart'      => self::getChartConfigs('sell'),
            'categories' => [
                ['category' => array_reverse($days_list)],
            ],
            'dataset'    => [
                ['seriesName' => 'sales',
                    'renderAs'    => 'line',
                    'parentYAxis' => 'S',
                    'data'        => array_reverse($sales_list),
                ],
                ['seriesName' => 'quantity',
                    'data'        => array_reverse($quantity_list),
                ],
            ],
        ];

        $data['chart'] = $obj;
        return $data;
    }

    private function getBoughtStats()
    {
        $data             = [];
        $data['snapshot'] = [];

        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
    		               COALESCE(sum(t.quantity), 0) as quantity');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
    		               COALESCE(sum(t.quantity), 0) as quantity');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL ' . $this->interval . ' DAY');
        $query                        = $this->db->get('');
        $data['snapshot']['interval'] = $query->row();

        // daily stats
        $purchasesByDay = [];
        $date       = new DateTime();
        for ($i = 0; $i < $this->interval; $i++) {
            $previous         = $date->sub(new DateInterval('P1D'));
            $str              = $previous->format('Y-m-d');
            $purchasesByDay[$str] = ['purchases' => 0, 'quantity' => 0];
        }

        $this->db->select('COALESCE(sum(t.price_total), 0) as purchases,
    					   COALESCE(sum(t.quantity), 0) as quantity,
    		               c.days as day');
        $this->db->from('calendar c');
        $this->db->join('transaction t', 'DATE(t.time) = c.days', 'left');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.time>= now() - INTERVAL ' . $this->interval . ' DAY');
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->group_by('c.days');
        $query  = $this->db->get('');
        $result = $query->result();

        foreach ($result as $day) {
            $purchasesByDay[$day->day] = ['purchases' => $day->purchases, 2, 'quantity' => $day->quantity, 0];
        }

        $purchases_list    = array();
        $days_list     = array();
        $quantity_list = array();

        foreach ($purchasesByDay as $key => $value) {
            array_push($days_list, ['label' => $key]);
            array_push($purchases_list, ['value' => $value['purchases']]);
            array_push($quantity_list, ['value' => $value['quantity']]);
        }

        // build chart
        $obj = [
            'chart'      => self::getChartConfigs('buy'),
            'categories' => [
                ['category' => array_reverse($days_list)],
            ],
            'dataset'    => [
                ['seriesName' => 'purchases',
                    'renderAs'    => 'line',
                    'parentYAxis' => 'S',
                    'data'        => array_reverse($purchases_list),
                ],
                ['seriesName' => 'quantity',
                    'data'        => array_reverse($quantity_list),
                ],
            ],
        ];

        $data['chart'] = $obj;
        return $data;
    }

    private function getProfitStats()
    {
        $data             = [];
        $data['snapshot'] = [];

        // totals
        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
    		               COALESCE(sum(p.quantity_profit), 0) as quantity');
        $this->db->from('profit p');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $this->db->where('p.timestamp_sell>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
    		               COALESCE(sum(p.quantity_profit), 0) as quantity');
        $this->db->from('profit p');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $this->db->where('p.timestamp_sell>= now() - INTERVAL ' . $this->interval . ' DAY');
        $query                        = $this->db->get('');
        $data['snapshot']['interval'] = $query->row();

        // daily stats
        $profitsByDay = [];
        $date         = new DateTime();
        for ($i = 0; $i < $this->interval; $i++) {
            $previous           = $date->sub(new DateInterval('P1D'));
            $str                = $previous->format('Y-m-d');
            $profitsByDay[$str] = ['profit' => 0, 'quantity' => 0];
        }

        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
    		               COALESCE(sum(p.quantity_profit), 0) as quantity,
    		               c.days as day');
        $this->db->from('calendar c');
        $this->db->join('profit p', 'DATE(p.timestamp_sell) = c.days', 'left');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell', 'left');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('p.timestamp_sell>= now() - INTERVAL ' . $this->interval . ' DAY');
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $this->db->group_by('c.days');
        $query  = $this->db->get('');
        $result = $query->result();

        foreach ($result as $day) {
            $profitsByDay[$day->day] = ['profit' => $day->profit, 2, 'quantity' => $day->quantity, 0];
        }

        $profits_list  = array();
        $days_list     = array();
        $quantity_list = array();

        foreach ($profitsByDay as $key => $value) {
            array_push($days_list, ['label' => $key]);
            array_push($profits_list, ['value' => $value['profit']]);
            array_push($quantity_list, ['value' => $value['quantity']]);
        }

        // build chart
        $obj = [
            'chart'      => self::getChartConfigs('profit'),
            'categories' => [
                ['category' => array_reverse($days_list)],
            ],
            'dataset'    => [
                ['seriesName' => 'profits',
                    'renderAs'    => 'line',
                    'parentYAxis' => 'S',
                    'data'        => array_reverse($profits_list),
                ],
                ['seriesName' => 'quantity',
                    'data'        => array_reverse($quantity_list),
                ],
            ],
        ];

        $data['chart'] = $obj;
        return $data;
    }

    private function getChartConfigs(string $type)
    {
        $title = '';
        $axis  = '';
        switch ($type) {
            case 'buy':
                $title = 'Purchases';
                $axis  = 'quantity bought';
                break;

            case 'sell':
                $title = 'Sales';
                $axis  = 'quantity sold';
                break;

            case 'profit':
                $title = 'Profits';
                $axis  = 'quantity re-sold';
                break;

            default:
                break;
        }

        return $arrData = array(
            'caption'                 => $title,
            'subcaption'              => 'for last ' . $this->interval . ' days',
            'subcaptionFontBold'      => "0",
            'paletteColors'           => "#0075c2,#1aaf5d,#f2c500",
            'anchorAlpha'             => '0',
            'xAxisname'               => "day",
            'pYAxisName'              => $axis,
            'sYAxisName'              => "profit",
            'sNumberSuffix'           => " ISK",
            'showAlternateHGridColor' => "0",
            'showPlotBorder'          => "0",
            'labelFontColor'          => "#fff",
            'labelFontSize'           => "14",
            'legendItemFontSize'      => "14",
            'usePlotGradientColor'    => "0",
            'baseFontColor'           => "#333333",
            'baseFont'                => "Helvetica Neue,Arial",
            'showBorder'              => "0",
            'showShadow'              => "0",
            'showCanvasBorder'        => "0",
            'legendBorderAlpha'       => "0",
            'legendShadow'            => "0",
            'showValues'              => "0",
            'divlineAlpha'            => "100",
            'divlineColor'            => "#999999",
            'divlineThickness'        => "1",
            'divLineDashed'           => "1",
            'divLineDashLen'          => "1",
            'numVisiblePlot'          => "12",
            'flatScrollBars'          => "1",
            'scrollheight'            => "10",
            'linethickness'           => "2",
            'formatnumberscale'       => "1",
            'labeldisplay'            => "ROTATE",
            'slantlabels'             => "1",
            'divLineAlpha'            => "40",
            'anchoralpha'             => "0",
            'animation'               => "1",
            'legendborderalpha'       => "20",
            'drawCrossLine'           => "1",
            'crossLineColor'          => "#f6a821",
            'crossLineAlpha'          => "100",
            'tooltipGrayOutColor'     => "#80bfff",
            'canvasBgAlpha'           => "0",
            'bgColor'                 => "#32353d",
            'bgAlpha'                 => "100",
            'legendBgColor'           => "#333",
            'outCnvBaseFontColor'     => "#fff",
            'outCnvBaseFontSize'      => "12",
            'pyaxisnamefontcolor'     => "#fff",
            'syaxisnamefontcolor'     => "#fff",
            'pyaxisnamefontsize'      => "16",
            'syaxisnamefontsize'      => "16",
            'captionFontColor'        => "#fff",
        );
    }

}
