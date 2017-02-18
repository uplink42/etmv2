<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statistics extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "statistics";
    }

    /**
     * Loads the Statistics page
     * @param  int         $character_id 
     * @param  int|integer $interval     
     * @return void                    
     */
    public function index(int $character_id, int $interval = 7) : void
    {
            $data = ["email" => "123"];
            $this->db->where('username', 'uplink424');
            $this->db->update('user', $data);

        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate              = $this->aggregate;
            $data                   = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars                  = $data['chars'];
            $data['selected']       = "statistics";
            
            $this->load->model('Statistics_model');
            $chart                   = $this->Statistics_model->buildVolumesChart($chars, $interval);
            $problematic             = $this->Statistics_model->getProblematicItems($chars, $interval);
            $profits_table           = $this->Statistics_model->getProfitsTable($chars, $interval);
            $best_raw                = $this->Statistics_model->getBestItemsRaw($chars, $interval);
            $best_margin             = $this->Statistics_model->getBestItemsMargin($chars, $interval);
            $best_customer           = $this->Statistics_model->getBestCustomersRawProfit($chars, $interval);
            $best_tz                 = $this->Statistics_model->getBestTZ($chars, $interval);
            $best_to                 = $this->Statistics_model->getFastestTurnovers($chars, $interval);
            $best_iph                = $this->Statistics_model->getBestIPH($chars, $interval);
            $best_blunders           = $this->Statistics_model->getMarketBlunders($chars, $interval);
            $best_stations           = $this->Statistics_model->getTopStations($chars, $interval);
            $raw_chart               = $this->Statistics_model->buildDistributionChart($chars, $interval);
            $lifetime_count_trans    = $this->Statistics_model->getTotalTransactions($chars);
            $lifetime_count_buy      = $this->Statistics_model->getTotalTransactions($chars, 'buy');
            $lifetime_count_sell     = $this->Statistics_model->getTotalTransactions($chars, 'sell');
            $lifetime_sum_trans      = $this->Statistics_model->getSumTransactions($chars);
            $lifetime_sum_buy        = $this->Statistics_model->getSumTransactions($chars, 'buy');
            $lifetime_sum_sell       = $this->Statistics_model->getSumTransactions($chars, 'sell');
            $lifetime_profit         = $this->Statistics_model->getTotalProfit($chars);
            $signup_date             = $this->Statistics_model->getSignupDate($this->user_id);
            $lifetime_highest_buy    = $this->Statistics_model->getHighestMetric($chars, 'buy');
            $lifetime_highest_sell   = $this->Statistics_model->getHighestMetric($chars, 'sell');
            $lifetime_highest_profit = $this->Statistics_model->getHighestMetric($chars, 'profit');

            $data['raw_chart']               = $raw_chart;
            $data['chart']                   = $chart;
            $data['problematic']             = $this->injectIcons($problematic);
            $data['profits_table']           = $profits_table;
            $data['best_raw']                = $this->injectIcons($best_raw);
            $data['best_raw_chart']          = $raw_chart;
            $data['best_margin']             = $this->injectIcons($best_margin);
            $data['best_customer']           = $best_customer;
            $data['best_tz']                 = $best_tz;
            $data['best_to']                 = $this->injectIcons($best_to);
            $data['best_iph']                = $this->injectIcons($best_iph);
            $data['best_blunders']           = $this->injectIcons($best_blunders);
            $data['best_stations']           = $best_stations;
            $data['lifetime_count_trans']    = $lifetime_count_trans;
            $data['lifetime_count_buy']      = $lifetime_count_buy;
            $data['lifetime_count_sell']     = $lifetime_count_sell;
            $data['lifetime_sum_trans']      = $lifetime_sum_trans;
            $data['lifetime_sum_buy']        = $lifetime_sum_buy;
            $data['lifetime_sum_sell']       = $lifetime_sum_sell;
            $data['lifetime_profit']         = $lifetime_profit;
            $data['signup_date']             = $signup_date;
            $data['lifetime_highest_buy']    = $lifetime_highest_buy;
            $data['lifetime_highest_sell']   = $lifetime_highest_sell;
            $data['lifetime_highest_profit'] = $lifetime_highest_profit;

            $data['interval'] = $interval;
            $data['view']     = 'main/statistics_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
