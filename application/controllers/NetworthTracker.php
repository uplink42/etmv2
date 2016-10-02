<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NetworthTracker extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "NetworthTracker";
    }

    public function index($character_id, $interval = 7)
    {
        if ($interval > 365) {
            $interval = 365;
        }

        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $this->load->model('NetworthTracker_model');
            $chart = $this->NetworthTracker_model->init($chars, $interval);

            $data['selected'] = "networth";

            $data['chart'] = $chart;
            $data['view']  = 'main/nwtracker_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
