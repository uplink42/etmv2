<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class Contracts extends MY_Controller
{
    protected $active;
    protected $inactive;
    protected $new;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "Contracts";

        if (isset($_GET['active'])) {
            if ($_GET['active'] == "All" ||
                $_GET['active'] == "ItemExchange" ||
                $_GET['active'] == "Courier" ||
                $_GET['active'] == "Loan" ||
                $_GET['active'] == "Auction") {
                $this->active = $_GET['active'];
            } else {
                $this->active = null;
            }
        } else {
            $this->active = null;
        }

        if (isset($_GET['inactive'])) {
            if ($_GET['inactive'] == "All" ||
                $_GET['inactive'] == "ItemExchange" ||
                $_GET['inactive'] == "Courier" ||
                $_GET['inactive'] == "Loan" ||
                $_GET['inactive'] == "Auction") {
                $this->inactive = $_GET['inactive'];
            } else {
                $this->inactive = null;
            }
        } else {
            $this->inactive = null;
        }

        if (isset($_REQUEST['new'])) {
            $this->new = (int)$_REQUEST['new'];
        };
    }

    public function index(int $character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->user_id)) {

            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "contracts";

            $this->load->model('Contracts_model');
            $actives   = $this->Contracts_model->getContracts($chars, $this->active, "active", $this->new);
            $inactives = $this->Contracts_model->getContracts($chars, $this->inactive, "inactive", $this->new);

            $data['actives_filter']   = $this->active;
            $data['inactives_filter'] = $this->inactive;
            $data['actives']          = $actives;
            $data['inactives']        = $inactives;
            $data['view']             = 'main/contracts_v';
            $this->load->view('main/_template_v', $data);
        }
    }

}
