<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Profits extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "profits";
        $this->load->model('Profits_model', 'profits');
    }

    /**
     * Loads the Profits page
     * @param  int         $character_id
     * @param  int|integer $interval
     * @param  int|null    $item_id
     * @return void
     */
    public function index($character_id, int $interval = 7): void
    {
        //$interval = 7;
        if ($this->enforce($character_id, $this->user_id)) {
            $this->Log->addEntry("visit " . $this->page, $this->user_id);
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "profits";
            $data['interval'] = $interval;
            //$data['item_id']  = $item_id;
            $data['view']     = 'main/profits_v';

            $data['layout']['page_title']     = "Profit Breakdown";
            $data['layout']['icon']           = "pe-7s-graph1";
            $data['layout']['page_aggregate'] = true;

            $this->twig->display('main/_template_v', $data);
        }
    }

    public function getProfitChart(int $character_id, int $interval = 1, bool $aggr, int $item_id = null)
    {
        $params = ['interval' => $interval,
            'item_id'             => $item_id];

        echo $this->buildData($character_id, $aggr, 'getProfitChartData', 'Profits_model', $params);
    }

    public function getProfitTable(int $character_id, int $interval = 1, bool $aggr)
    {
        $params = ['character_id' => $character_id,
            'aggr'                    => $aggr,
            'interval'                => $interval,
            'user_id'                 => $this->user_id,
            'defs'                    => $_REQUEST];

        echo $this->buildData($character_id, $aggr, 'getProfits', 'Profits_model', $params);
    }
}
