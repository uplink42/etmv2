<?php declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Home_model');
        $this->db->cache_off();
    }

    public function index()
    {
        $data = ["view" => "home/home_v", "stats" => $this->Home_model->getStats()];
        $this->load->view('home/_template', $data);
    }



}
