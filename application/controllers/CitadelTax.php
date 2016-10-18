<?php declare (strict_types = 1);
defined('BASEPATH') or exit('No direct script access allowed');

class CitadelTax extends MY_Controller
{
    private $citadel;
    private $tax;
    private $character_id;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "CitadelTax";
        $this->load->model('CitadelTax_model');

        $this->citadel      = $_REQUEST['citadel'] ?? '';
        $this->tax          = $_REQUEST['tax'] ?? '';
        $this->character_id = $_REQUEST['character'] ?? '';

        settype($this->citadel, 'string');
        settype($this->tax, 'float');
        settype($this->character_id, 'int');
    }

    public function index(int $character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->user_id)) {

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
        $input  = $_REQUEST['term'];
        $result = $this->CitadelTax_model->queryCitadels($input);

        echo json_encode($result);
    }

    public function addTax()
    {
        $citadel_id = $this->CitadelTax_model->getCitadelID($this->citadel);
        if ($citadel_id) {
            //check if character belongs
            if ($this->ValidateRequest->checkCharacterBelong($this->character_id, $this->user_id)) {
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
    }

    public function getTaxList(int $character_id)
    {
        echo json_encode($this->CitadelTax_model->taxList($character_id));
    }

    public function removeTax(int $character_id, int $tax_id)
    {
        if ($this->ValidateRequest->checkCitadelOwnership($character_id, $tax_id)) {
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
