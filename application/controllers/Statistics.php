<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class Statistics extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "Statistics";
    }

    public function index(int $character_id, int $interval = 7)
    {
        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate              = $this->aggregate;
            $data                   = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars                  = $data['chars'];
            $data['selected']       = "statistics";
            
            $this->load->model('Statistics_model');
            $chart                  = $this->Statistics_model->buildVolumesChart($chars, $interval);
            $problematic            = $this->Statistics_model->getProblematicItems($chars, $interval);
            $profits_table          = $this->Statistics_model->getProfitsTable($chars, $interval);
            $best_raw               = $this->Statistics_model->getBestItemsRaw($chars, $interval);
            $best_margin            = $this->Statistics_model->getBestItemsMargin($chars, $interval);
            $best_customer          = $this->Statistics_model->getBestCustomersRawProfit($chars, $interval);
            $best_tz                = $this->Statistics_model->getBestTZ($chars, $interval);
            $best_to                = $this->Statistics_model->getFastestTurnovers($chars, $interval);
            $best_iph               = $this->Statistics_model->getBestIPH($chars, $interval);
            $best_blunders          = $this->Statistics_model->getMarketBlunders($chars, $interval);
            $best_stations          = $this->Statistics_model->getTopStations($chars, $interval);
            
            $raw_chart              = $this->Statistics_model->buildDistributionChart($chars, $interval);
            
            $data['raw_chart']      = $raw_chart;
            $data['chart']          = $chart;
            $data['problematic']    = $this->injectIcons($problematic);
            $data['profits_table']  = $profits_table;
            $data['best_raw']       = $this->injectIcons($best_raw);
            $data['best_raw_chart'] = $raw_chart;
            $data['best_margin']    = $this->injectIcons($best_margin);
            $data['best_customer']  = $best_customer;
            $data['best_tz']        = $best_tz;
            $data['best_to']        = $this->injectIcons($best_to);
            $data['best_iph']       = $this->injectIcons($best_iph);
            $data['best_blunders']  = $this->injectIcons($best_blunders);
            $data['best_stations']  = $best_stations;

            $data['interval'] = $interval;
            $data['view']     = 'main/statistics_v';
            $this->load->view('main/_template_v', $data);
        }
    }

}
