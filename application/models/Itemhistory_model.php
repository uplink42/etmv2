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

    public function getItemName(int $id)
    {
        $this->db->select('name, eve_iditem');
        $this->db->where('eve_iditem', $id);
        $query = $this->db->get('item');
        return $query->row();
    }

    public function getStatsByInterval(array $configs)
    {
        extract($configs);
        $this->chars    = $chars;
        $this->item_id  = $item_id;
        $this->interval = $interval;

        $result = [];

        $result['item']   = $this->getItemName($item_id);
        $result['sell']   = $this->getSoldStats();
        $result['buy']    = $this->getBoughtStats();
        $result['profit'] = $this->getProfitStats();

        return json_encode($result);
    }

    private function getSoldStats()
    {
        $data             = [];
        $data['snapshot'] = [];

        // lifetime
        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Sell');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $query                        = $this->db->get('');
        $data['snapshot']['lifetime'] = $query->row();

        // totals
        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Sell');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
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

        // lifetime
        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $query                        = $this->db->get('');
        $data['snapshot']['lifetime'] = $query->row();

        // totals
        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        $this->db->select('COALESCE(sum(t.price_total), 0) as total,
                           COALESCE(sum(t.quantity), 0) as quantity,
                           COALESCE(COALESCE(sum(t.price_total), 0) / COALESCE(sum(t.quantity), 0), 0) as avg');
        $this->db->from('transaction t');
        $this->db->where('t.transaction_type', 'Buy');
        $this->db->where('t.character_eve_idcharacter IN ' . $this->chars);
        $this->db->where('t.item_eve_iditem', $this->item_id);
        $this->db->where('t.time>= now() - INTERVAL ' . $this->interval . ' DAY');
        $query                        = $this->db->get('');
        $data['snapshot']['interval'] = $query->row();

        // daily stats
        $purchasesByDay = [];
        $date           = new DateTime();
        for ($i = 0; $i < $this->interval; $i++) {
            $previous             = $date->sub(new DateInterval('P1D'));
            $str                  = $previous->format('Y-m-d');
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

        $purchases_list = array();
        $days_list      = array();
        $quantity_list  = array();

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

        // lifetime
        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
                           COALESCE(sum(p.quantity_profit), 0) as quantity,
                           COALESCE(sum(p.profit_unit)/sum(t1.price_unit)*100,0) as margin,
                           COALESCE(COALESCE(sum(p.quantity_profit * p.profit_unit), 0) / COALESCE(sum(p.quantity_profit), 0), 0) as avg_profit');
        $this->db->from('profit p');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy', 'left');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell', 'left');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $query                        = $this->db->get('');
        $data['snapshot']['lifetime'] = $query->row();

        // today
        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
                           COALESCE(sum(p.quantity_profit), 0) as quantity,
                           COALESCE(sum(p.profit_unit)/sum(t1.price_unit)*100,0) as margin,
                           COALESCE(COALESCE(sum(p.quantity_profit * p.profit_unit), 0) / COALESCE(sum(p.quantity_profit), 0), 0) as avg_profit');
        $this->db->from('profit p');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy', 'left');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell', 'left');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $this->db->where('p.timestamp_sell>= now() - INTERVAL 1 DAY');
        $query                   = $this->db->get('');
        $data['snapshot']['day'] = $query->row();

        // interval
        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
                           COALESCE(sum(p.quantity_profit), 0) as quantity,
                           COALESCE(sum(p.profit_unit)/sum(t1.price_unit)*100,0) as margin,
                           COALESCE(COALESCE(sum(p.quantity_profit * p.profit_unit), 0) / COALESCE(sum(p.quantity_profit), 0), 0) as avg_profit');
        $this->db->from('profit p');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy', 'left');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell', 'left');
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
            $profitsByDay[$str] = ['profit' => 0, 'quantity' => 0, 'margin' => 0, 'avg_profit' => 0];
        }

        $this->db->select('COALESCE(sum(p.quantity_profit * p.profit_unit), 0) as profit,
                           COALESCE(sum(p.quantity_profit), 0) as quantity,
                           COALESCE(sum(p.profit_unit)/sum(t1.price_unit)*100,0) as margin,
                           c.days as day');
        $this->db->from('calendar c');
        $this->db->join('profit p', 'DATE(p.timestamp_sell) = c.days', 'left');
        $this->db->join('transaction t1', 't1.idbuy = p.transaction_idbuy_buy');
        $this->db->join('transaction t2', 't2.idbuy = p.transaction_idbuy_sell', 'left');
        $this->db->where('p.characters_eve_idcharacters_OUT IN ' . $this->chars);
        $this->db->where('p.timestamp_sell>= now() - INTERVAL ' . $this->interval . ' DAY');
        $this->db->where('t2.item_eve_iditem', $this->item_id);
        $this->db->group_by('c.days');
        $query  = $this->db->get('');
        $result = $query->result();

        foreach ($result as $day) {
            $profitsByDay[$day->day] = [
                'profit'     => $day->profit,
                'quantity'   => $day->quantity,
                'margin'     => $day->margin,
            ];
        }

        $profits_list     = array();
        $margins_list     = array();
        $days_list        = array();
        $quantity_list    = array();

        foreach ($profitsByDay as $key => $value) {
            array_push($days_list, ['label' => $key]);
            array_push($profits_list, ['value' => $value['profit']]);
            array_push($margins_list, ['value' => $value['margin']]);
            array_push($quantity_list, ['value' => $value['quantity']]);
        }

        // build profit chart
        $obj_profit = [
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

        // build margin chart
        $obj_margin = [
            'chart'      => self::getChartConfigs('profit_margin'),
            'categories' => [
                ['category' => array_reverse($days_list)],
            ],
            'dataset'    => [
                ['seriesName' => 'profit',
                    'renderAs'    => 'line',
                    'parentYAxis' => 'S',
                    'data'        => array_reverse($profits_list),
                ],
                ['seriesName' => 'margin',
                    'data'        => array_reverse($margins_list),
                ],
            ],
        ];

        $data['chart']        = $obj_profit;
        $data['margin_chart'] = $obj_margin;
        return $data;
    }

    private function getChartConfigs(string $type)
    {
        $title          = '';
        $axisLeftSuffix = '';
        switch ($type) {
            case 'buy':
                $title           = 'Purchases';
                $axisLeft        = 'quantity bought';
                $axisRight       = 'total ISK in purchases';
                $axisRightSuffix = ' ISK';
                break;

            case 'sell':
                $title           = 'Sales';
                $axisLeft        = 'quantity sold';
                $axisRight       = 'total ISK in sales';
                $axisRightSuffix = ' ISK';
                break;

            case 'profit':
                $title           = 'Profits';
                $axisLeft        = 'quantity re-sold';
                $axisRight       = 'total ISK in profit';
                $axisRightSuffix = ' ISK';
                break;

            case 'profit_margin':
                $title           = 'Profit Margins';
                $axisLeft        = 'profit margin (%)';
                $axisRight       = 'total ISK in profit';
                $axisRightSuffix = ' ISK';
                $axisLeftSuffix  = ' %';
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
            'pYAxisName'              => $axisLeft,
            'sYAxisName'              => $axisRight,
            'sNumberSuffix'           => $axisRightSuffix,
            'pNumberSuffix'           => $axisLeftSuffix,
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
