<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profits extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->load->library('session');

    }

    public function index($character_id, $interval = 7, $item_id = null)
    {
        if($interval>365) {$interval = 365;}
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];

            $data['selected'] = "profits";
            $this->load->model('Profits_model');
            
            $profits = $this->Profits_model->getProfits($chars, $interval, $item_id);
            $profits_r = $profits['result'];
            $profits_c = $profits['count'];
            
            if($profits_c>200) {
                $img = false;
            } else {
                $img = true;
            }

            $data['img'] = $img;
            $data['profits'] = $profits_r;
            $data['interval'] = $interval;
            $data['view']           = 'main/profits_v';
            $this->load->view('main/_template_v', $data);
        }
    }
}
