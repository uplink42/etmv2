<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_sum extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('Home_model', 'home');
    }

    /**
     * Begins the price updater
     * Fetches all items's daily prices from CREST
     * @return void 
     */
    public function index()
    {
        if (!$this->input->is_cli_request()) {
            die('Unauthorized');
        }

        $data = $this->home->saveStats();
    }
}
