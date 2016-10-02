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
        $this->page = "CitadelTax";

        isset($_REQUEST['citadel']) ? $this->citadel        = $_REQUEST['citadel'] : '';
        isset($_REQUEST['tax']) ? $this->tax                = $_REQUEST['tax'] : '';
        isset($_REQUEST['character']) ? $this->character_id = $_REQUEST['character'] : '';
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "citadeltax";

            $this->getTaxList($character_id);

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
            if ($this->ValidateRequest->checkCharacterBelong($this->character_id, $this->session->iduser)) {
                if ($this->CitadelTax_model->setTax($citadel_id, $this->character_id, $this->tax)) {
                    $msg    = Msg::TAX_SET_SUCCESS;
                    $notice = "success";
                } else {
                    $msg    = Msg::DB_ERROR;
                    $notice = "error";
                }
            } else {
                $msg    = Msg::INVALID_REQUEST;
                $notice = "error";
            }
        } else {
            $msg    = Msg::CITADEL_NOT_FOUND;
            $notice = "error";
        }

        echo json_encode(array("notice" => $notice, "message" => $msg));
        //echo json_encode(array("tnc" => $this->character_id));
    }

    public function getTaxList($character_id)
    {
        $this->load->model('CitadelTax_model');
        echo json_encode($this->CitadelTax_model->taxList($character_id));
    }

    public function removeTax($character_id, $tax_id)
    {
        $this->load->model('CitadelTax_model');
        if ($this->CitadelTax_model->checkOwnership($character_id, $tax_id)) {
            if ($this->CitadelTax_model->removeTax($tax_id)) {
                $msg    = Msg::TAX_REMOVE_SUCCESS;
                $notice = "success";
            } else {
                $msg    = Msg::DB_ERROR;
                $notice = "error";
            }
        } else {
            $msg    = Msg::INVALID_REQUEST;
            $notice = "error";
        }

        echo json_encode(array("notice" => $notice, "message" => $msg));
    }

}
