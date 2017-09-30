<?php
defined('BASEPATH') or exit('No direct script access allowed');
final class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Profit_model', 'profit');
        $this->load->model('New_info_model', 'new_info');
        $this->load->model('History_model', 'history');
        $this->load->helper('log');
        $this->load->helper('chart');
        $this->page = "dashboard";
    }

    /**
     * Loads the dashboard page
     * @param  int         $character_id
     * @param  int|integer $interval
     * @return void
     */
    public function index($idCharacter, int $interval = 1): void
    {
        $interval = $interval < 7 ? $interval : 7;
        if ($this->enforce($idCharacter, $this->idUser)) {
            Log::addEntry("visit " . $this->page, $this->idUser);

            $aggregate = $this->aggregate;
            $data      = $this->loadCommon($idCharacter, $this->idUser, $aggregate);
            $chars     = $data['chars'];
            $data['selected']       = "dashboard";
            $data['interval']       = $interval;
            $data['week_profits']   = Chart::buildSparkline($this->history->getWeekProfits($chars));
            $data['new_info']       = $this->new_info->getNewInfo($chars);
            $data['profits_trends'] = $this->profit->getTotalProfitsTrends($chars);
            
            $data['layout']['page_title']     = "Dashboard";
            $data['layout']['icon']           = "pe-7s-shield";
            $data['layout']['page_aggregate'] = true;
            $data['view'] = 'main/dashboard_v';
            $this->twig->display('main/_template_v', $data);
        }
    }

    public function getPieChart(int $character_id, bool $aggr = false): void
    {
        $params = [];
        echo $this->buildData($character_id, $aggr, 'getPieChartData', 'Dashboard_model', $params);
    }

    public function getProfitTable(int $character_id, int $interval = 3, bool $aggr = false): void
    {
        $params = ['interval' => $interval,
            'user_id'             => $this->user_id,
            'defs'                => $_REQUEST,
        ];
        echo $this->buildData($character_id, $aggr, 'getProfits', 'Dashboard_model', $params);
    }
}