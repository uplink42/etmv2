<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_outposts extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_outposts_model', 'outposts');
        $this->load->model('common/ValidateRequest');
    }

    /**
     * Begins the outpost and citadel updater
     * Fetches the most recent stations from the api's
     * @return void 
     */
    public function index() : void
    {
        if (!$this->input->is_cli_request()) {
            die();
        }

        if($this->ValidateRequest->getCrestStatus()) {
            $count =  $this->outposts->getOutposts();
            echo "Outpost list updated. Total: " . $count;

            $citadels = $this->outposts->getCitadels();
            echo "\n" . "Citadel list updated. Total: " . $citadels;
        } else {
            echo Msg::CREST_CONNECT_FAILURE;
        }
    }
}
