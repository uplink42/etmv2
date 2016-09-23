<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statistics extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->load->library('session');
        $this->page = "Statistics";
    }

    public function index($character_id, $interval = 7)
    {
        if($interval>365) $interval = 365;
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "statistics";

            $this->load->model('Statistics_model');
            $chart = $this->Statistics_model->buildVolumesChart($chars, $interval);

            $data['chart'] = $chart;
            $data['interval'] = $interval;
            $data['view']           = 'main/statistics_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    
}
