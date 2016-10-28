<?php

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
        $this->load->view('home/_template');
    }

    public function getTransactionCount()
    {
        $result = $this->Home_model->getTransactions();
        echo json_encode($result);            
    }

    public function getProfitTotal()
    {
        $result = $this->Home_model->getProfits();
        echo json_encode($result);        
    }

    public function getCharacterCount()
    {
        $result = $this->Home_model->getCharacters();
        echo json_encode($result);
    }

    public function getKeysCount()
    {
        $result = $this->Home_model->getKeys();
        echo json_encode($result);
    }

}
