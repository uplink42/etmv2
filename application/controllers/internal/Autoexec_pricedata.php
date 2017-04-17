<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_pricedata extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_pricedata_model', 'pricedata');
        $this->load->model('common/ValidateRequest');
    }

    /**
     * Begins the price updater
     * Fetches all items's daily prices from CREST
     * @return void 
     */
    public function index() : void
    {
        if (!$this->input->is_cli_request()) {
            die();
        }
        
        if($this->ValidateRequest->getCrestStatus()) {
            $count = $this->pricedata->getPrices();
            echo "Item price data updated. Total items: " . $count;
        } else {
            echo Msg::CREST_CONNECT_FAILURE;
        }
    }
}
