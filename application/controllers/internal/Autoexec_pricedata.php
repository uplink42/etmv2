<?php
//defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

class Autoexec_pricedata extends CI_Controller
{

    //update all totals, apis and character data
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_pricedata_model', 'pricedata');
        $this->load->model('common/ValidateRequest');
    }

    public function index()
    {
        if($this->ValidateRequest->getCrestStatus()) {
            $count = $this->pricedata->getPrices();
            echo "Item price data updated. Total items: " . $count;
        } else {
            echo Msg::CREST_CONNECT_FAILURE;
        }
    }

}
