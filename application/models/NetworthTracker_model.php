<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class NetworthTracker_model extends CI_Model
{
    private $chars;
    private $interval;
    private $styles          = [];
    private $full_array      = [];
    private $chart_options   = [];
    private $categories_data = [];

    private $wallet_data = [];
    private $assets_data = [];
    private $orders_data = [];
    private $escrow_data = [];
    private $total_data  = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize the chart settings and dispatches each method 
     * of results
     * @param  string $chars    
     * @param  int    $interval 
     * @return string           
     */
    public function init(array $config): string
    {
        extract($config);
        $this->interval = $interval;
        $this->chars    = $chars;

        $this->chart_options = array(
            'caption'            => 'Networth Evolution',
            'subcaption'         => 'for last ' . $interval . ' days',
            'xaxisname'          => 'day',
            'yaxisname'          => 'ISK',
            'captionFontSize'    => '20',
            'subcaptionFontSize' => '14',
            'subcaptionFontBold' => '0',
            'showValues'         => '0',
        );

        $this->styles = array(
            'styles' => array(
                'definition'  => array(
                    0 => array(
                        'name' => 'captionFont',
                        'type' => 'font',
                        'size' => '15',
                    ),
                ),
                'application' => array(
                    0 => array(
                        'toobject' => 'caption',
                        'styles'   => 'captionfont',
                    ),
                ),
            ),
        );

        $this->daysDataset();
        $this->walletDataset();
        $this->assetsDataset();
        $this->ordersDataset();
        $this->escrowDataset();
        $this->totalDataset();

        return $this->chartBuilder();
    }

    /**
     * Aggregates all individual data and produces the final chart
     * object
     * @return string json 
     */
    public function chartBuilder(): string
    {
        $categoryValues = array(
            array(
                'category' => $this->categories_data,
            ),
        );

        $walletValues = array(
            'seriesname' => 'Wallet',
            'data'       => $this->wallet_data,
        );

        $assetValues = array(
            'seriesname' => 'Assets',
            'data'       => $this->assets_data,
        );

        $sellValues = array(
            'seriesname' => 'Sell Orders',
            'data'       => $this->orders_data,
        );

        $escrowValues = array(
            'seriesname' => 'Escrow',
            'data'       => $this->escrow_data,
        );

        $totalValues = array(
            'seriesname' => 'Total',
            'data'       => $this->total_data,
        );

        $this->full_array['chart']      = $this->chart_options;
        $this->full_array['categories'] = $categoryValues;
        $this->full_array['dataset']    = array($walletValues, $assetValues, $sellValues, $escrowValues, $totalValues);
        return json_encode($this->full_array, 1);
    }

    /**
     * Gathers the days axis
     * @return string json 
     */
    private function daysDataset()
    {
        $this->db->select('date');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query   = $this->db->get('net_history');
        $getDays = $query->result_array();

        foreach ($getDays as $day) {
            array_push($this->categories_data, array(
                'label' => $day['date'])
            );
        }
        json_encode($this->categories_data, 1);
    }

    /**
     * Gathers the wallet data
     * @return void 
     */
    private function walletDataset()
    {
        $this->db->select('sum(total_wallet) as total_wallet');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query         = $this->db->get('net_history');
        $getWalletData = $query->result_array();

        foreach ($getWalletData as $wallet) {
            array_push($this->wallet_data, array(
                'value' => $wallet['total_wallet'],
            ));
        }
    }

    /**
     * Gathers the assets data
     * @return void
     */
    private function assetsDataset()
    {
        $this->db->select('sum(total_assets) as total_assets');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query         = $this->db->get('net_history');
        $getAssetsData = $query->result_array();

        foreach ($getAssetsData as $assets) {
            array_push($this->assets_data, array(
                'value' => $assets['total_assets'],
            ));
        }
    }

    /**
     * Gathers the sell orders data
     * @return void 
     */
    private function ordersDataset()
    {
        $this->db->select('sum(total_sell) as total_sell');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query       = $this->db->get('net_history');
        $getSellData = $query->result_array();

        foreach ($getSellData as $sell) {
            array_push($this->orders_data, array(
                'value' => $sell['total_sell'],
            ));
        }
    }

    /**
     * Gathers the escrow data
     * @return void 
     */
    private function escrowDataset()
    {
        $this->db->select('sum(total_escrow) as total_escrow');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query         = $this->db->get('net_history');
        $getEscrowData = $query->result_array();

        foreach ($getEscrowData as $escrow) {
            array_push($this->escrow_data, array(
                'value' => $escrow['total_escrow'],
            ));
        }
    }

    /**
     * Gathers the total networth data
     * @return void
     */
    private function totalDataset()
    {
        $this->db->select('sum(total_wallet+total_escrow+total_sell+total_assets) AS grandtotal');
        $this->db->where('date >= DATE_SUB(NOW(), INTERVAL ' . $this->interval . ' DAY)');
        $this->db->where('characters_eve_idcharacters IN ' . $this->chars);
        $this->db->group_by('date');
        $this->db->order_by('date', 'asc');
        $query        = $this->db->get('net_history');
        $getTotalData = $query->result_array();

        foreach ($getTotalData as $total) {
            array_push($this->total_data, array(
                'value' => $total['grandtotal'],
            ));
        }
    }
}
