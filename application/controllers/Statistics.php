<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Statistics extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "statistics";
        $this->load->model('Statistics_model', 'stats');
    }

    /**
     * Loads the Statistics page
     * @param  int         $character_id
     * @param  int|integer $interval
     * @return void
     */
    public function index($character_id, int $interval = 7): void
    {
        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $this->user_id)) {
            $this->Log->addEntry("visit " . $this->page, $this->user_id);
            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "statistics";

            $profits_table           = $this->stats->getProfitsTable($chars, $interval);
            $best_customer           = $this->stats->getBestCustomersRawProfit($chars, $interval);
            $best_tz                 = $this->stats->getBestTZ($chars, $interval);
            $best_to                 = $this->stats->getFastestTurnovers($chars, $interval);
            $best_blunders           = $this->stats->getMarketBlunders($chars, $interval);
            $best_stations           = $this->stats->getTopStations($chars, $interval);
            
            $lifetime_count_trans    = $this->stats->getTotalTransactions($chars);
            $lifetime_count_buy      = $this->stats->getTotalTransactions($chars, 'buy');
            $lifetime_count_sell     = $this->stats->getTotalTransactions($chars, 'sell');
            $lifetime_sum_trans      = $this->stats->getSumTransactions($chars);
            $lifetime_sum_buy        = $this->stats->getSumTransactions($chars, 'buy');
            $lifetime_sum_sell       = $this->stats->getSumTransactions($chars, 'sell');
            $lifetime_profit         = $this->stats->getTotalProfit($chars);
            $signup_date             = $this->stats->getSignupDate($this->user_id);
            $lifetime_highest_buy    = $this->stats->getHighestMetric($chars, 'total_buy');
            $lifetime_highest_sell   = $this->stats->getHighestMetric($chars, 'total_sell');
            $lifetime_highest_profit = $this->stats->getHighestMetric($chars, 'total_profit');

            $data['profits_table']           = $profits_table;
            $data['best_customer']           = $best_customer;
            $data['best_tz']                 = $best_tz;
            $data['best_to']                 = injectIcons($best_to);
            $data['best_blunders']           = injectIcons($best_blunders);
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

            $data['layout']['page_title']     = "Statistics";
            $data['layout']['icon']           = "pe-7s-display2";
            $data['layout']['page_aggregate'] = true;

            $data['interval'] = $interval;
            $data['view']     = 'main/statistics_v';
            $this->twig->display('main/_template_v', $data);
        }
    }

    public function getVolumesChart(int $character_id, int $interval = 1, bool $aggr = false): void
    {
        $params = ['interval' => $interval];
        echo $this->buildData($character_id, $aggr, 'buildVolumesChart', 'Statistics_model', $params);
    }

    public function getDistributionChart(int $character_id, int $interval = 1, bool $aggr = false): void
    {
        $params = ['interval' => $interval];
        echo $this->buildData($character_id, $aggr, 'buildDistributionChart', 'Statistics_model', $params);
    }

    public function getBestItemsProfit(int $character_id, int $interval = 1, bool $aggr)
    {
        $params = ['character_id' => $character_id,
                   'aggr'         => $aggr,
                   'interval'     => $interval,
                   'defs'         => $_REQUEST];

        echo $this->buildData($character_id, $aggr, 'getBestItemsRaw', 'Statistics_model', $params);
    }

    public function getBestItemsMargin(int $character_id, int $interval = 1, bool $aggr)
    {
        $params = ['character_id' => $character_id,
                   'aggr'         => $aggr,
                   'interval'     => $interval,
                   'defs'         => $_REQUEST];

        echo $this->buildData($character_id, $aggr, 'getBestItemsMargin', 'Statistics_model', $params);
    }

    public function getProblematicItems(int $character_id, int $interval = 1, bool $aggr)
    {
        $params = ['character_id' => $character_id,
                   'aggr'         => $aggr,
                   'interval'     => $interval,
                   'defs'         => $_REQUEST];

        echo $this->buildData($character_id, $aggr, 'getProblematicItems', 'Statistics_model', $params);
    }

    public function getBestIPH(int $character_id, int $interval = 1, bool $aggr)
    {
        $params = ['character_id' => $character_id,
                   'aggr'         => $aggr,
                   'interval'     => $interval,
                   'defs'         => $_REQUEST];

        echo $this->buildData($character_id, $aggr, 'getBestIPH', 'Statistics_model', $params);
    }
}
