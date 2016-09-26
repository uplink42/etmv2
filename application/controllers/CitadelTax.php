<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CitadelTax extends MY_Controller
{
    private $significant;
    private $citadel;
    private $tax;
    private $character_id;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->page = "CitadelTax";

        isset($_REQUEST['citadel']) ? $this->citadel        = $_REQUEST['citadel'] : '';
        isset($_REQUEST['tax']) ? $this->tax                = $_REQUEST['tax'] : '';
        isset($_REQUEST['character']) ? $this->character_id = $_REQUEST['character'] : '';
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "assets";

            $data['view'] = 'main/citadeltax_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function searchCitadels()
    {
        $input = $_REQUEST['term'];

        $this->load->model('CitadelTax_model');
        $result = $this->CitadelTax_model->queryCitadels($input);

        echo json_encode($result);
    }

    public function addTax()
    {
        $this->load->model('CitadelTax_model');
        $citadel_id = $this->CitadelTax_model->getCitadelID($this->citadel);
        if ($citadel_id) {
            //check if character belongs
            $this->load->model('Nav_model');
            if ($this->Nav_model->checkCharacterBelong($this->character_id, $this->session->iduser)) {
                if ($this->CitadelTax_model->setTax($citadel_id, $this->character_id, $this->tax)) {
                    $msg    = "Tax set sucessfully.";
                    $notice = "success";
                } else {
                    $msg    = "Unexpected failure connecting to database. Try again.";
                    $notice = "error";
                }
            } else {
                $msg    = "Invalid request";
                $notice = "error";
            }
        } else {
            $msg    = "Unable to find this Citadel.";
            $notice = "error";
        }

        echo json_encode(array("notice" => $notice, "message" => $msg));
        //echo json_encode(array("tnc" => $this->character_id));
    }

}
