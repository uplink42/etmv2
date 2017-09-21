<?php

defined('BASEPATH') or exit('No direct script access allowed');

final class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stats_model', 'stats');
        $this->db->cache_off();
    }

    /**
     * Loads the homepage
     * @return void
     */
    public function index(): void
    {
        $data = ["view" => "home/home_v"];
        $this->load->view('home/_template');
    }

    /**
     * Returns the website stats
     * @return string json
     */
    public function getAll(): void
    {
        $data = $this->stats->getStats();
        echo json_encode($data);
    }
}
