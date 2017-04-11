<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_market_types extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_market_types_model', 'types');
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
            //die();
        }
        
        if($this->ValidateRequest->getCrestStatus()) {
            $count = $this->types->getItems();
            echo "Item list imported successfully: " . $count;
        } else {
            echo Msg::CREST_CONNECT_FAILURE;
        }
    }
}
