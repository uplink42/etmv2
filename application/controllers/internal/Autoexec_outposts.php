<?php defined('BASEPATH') or exit('No direct script access allowed');

class Autoexec_outposts extends CI_Controller
{

    //update all totals, apis and character data
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_outposts_model', 'outposts');
        $this->load->model('common/ValidateRequest');
    }

    public function index()
    {
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
