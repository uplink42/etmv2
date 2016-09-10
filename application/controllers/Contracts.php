<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contracts extends MY_Controller
{
    protected $active;
    protected $inactive;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->load->library('session');

        if(isset($_GET['active'])) {
            if ($_GET['active'] == "All" || 
                $_GET['active'] == "ItemExchange" ||
                $_GET['active'] == "Courier" || 
                $_GET['active'] == "Loan" || 
                $_GET['active'] == "Auction") {
                $this->active = $_GET['active'];
            } else {
                $this->active = false;
            }
        } else {
            $this->active = false;
        }

        if(isset($_GET['inactive'])) {
            if ($_GET['inactive'] == "All" || 
                $_GET['inactive'] == "ItemExchange" ||
                $_GET['inactive'] == "Courier" || 
                $_GET['inactive'] == "Loan" || 
                $_GET['inactive'] == "Auction") {
                $this->inactive = $_GET['inactive'];
            } else {
                $this->inactive = false;
            }
        } else {
            $this->inactive = false;
        }
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];
            $data['selected'] = "contracts";

            $this->load->model('Contracts_model');
            $actives = $this->Contracts_model->getContracts($chars, $this->active, "active");
            $inactives = $this->Contracts_model->getContracts($chars, $this->inactive, "inactive");

            $data['actives_filter'] = $this->active;
            $data['inactives_filter'] = $this->inactive;
            $data['actives'] = $actives;
            $data['inactives'] = $inactives;
            $data['view']           = 'main/contracts_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    
}
