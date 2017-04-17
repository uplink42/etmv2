<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Citadeltax extends MY_Controller
{
    private $citadel;
    private $tax;
    private $character_id;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "citadeltax";
        $this->load->model('CitadelTax_model');

        $this->citadel      = $_REQUEST['citadel'] ?? '';
        $this->tax          = $_REQUEST['tax'] ?? '';
        $this->character_id = $_REQUEST['character'] ?? '';

        settype($this->citadel, 'string');
        settype($this->tax, 'float');
        settype($this->character_id, 'int');
    }

    /**
     * Loads the Citadel tax page
     * @param  int    $character_id 
     * @return void              
     */
    public function index(int $character_id) : void
    {
        if ($this->enforce($character_id, $user_id = $this->user_id)) {
            $aggregate        = $this->aggregate;
            $data             = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars            = $data['chars'];
            $data['selected'] = "citadeltax";
            $data['view']     = 'main/citadeltax_v';

            $data['layout']['page_title']     = "Citadel Tax";
            $data['layout']['icon']           = "pe-7s-link";
            $data['layout']['page_aggregate'] = true;

            $this->twig->display('main/_template_v', $data);
            //$this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Returns Citadels autocomplete results
     * @return string json
     */
    public function searchCitadels() : void
    {
        $input  = $_REQUEST['term'];
        $result = $this->CitadelTax_model->queryCitadels($input);
        echo json_encode($result);
    }

    /**
     * Validates and adds a tax to a certain citadel
     * @return string json
     */
    public function addTax() : void
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

    /**
     * Returns the citadel tax list for a user
     * @param  int    $character_id 
     * @return string json               
     */
    public function getTaxList(int $character_id) : void
    {
        echo json_encode($this->CitadelTax_model->taxList($character_id));
    }

    /**
     * Removes a citadel tax entry
     * @param  int    $character_id
     * @param  int    $tax_id      
     * @return string json              
     */
    public function removeTax(int $character_id, int $tax_id) : void
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
