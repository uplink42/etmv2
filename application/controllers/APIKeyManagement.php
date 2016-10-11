<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiKeyManagement extends MY_Controller
{
    private $significant;
    private $keyid;
    private $vcode;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "APIKeyManagement";
        $this->load->model('ApiKeyManagement_model');

        if(!empty($_REQUEST['keyid']) && !empty($_REQUEST['vcode'])) {
            $this->keyid = $_REQUEST['keyid'];
            $this->vcode = $_REQUEST['vcode'];
        }
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];
            $data['selected'] = "apikey";

            $data['view']     = 'main/apimanagement_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function getCharacters()
    {
        $data = $this->ApiKeyManagement_model->getCharacterList($this->session->iduser);
        echo json_encode($data);
    }

    public function removeCharacter($id_character)
    {
        $this->load->model('ValidateRequest');
        if ($this->ValidateRequest->checkCharacterBelong($id_character, $this->session->iduser)) {
            if($this->ApiKeyManagement_model->removeCharacter($id_character)) {
                $notice = "success";
                $msg = Msg::CHARACTER_REMOVE_SUCCESS;
            } else {
                $notice = "error";
                $msg = Msg::CHARACTER_REMOVE_ERROR;
            }
        } else {
            $notice = "error";
            $msg = Msg::INVALID_REQUEST;
        }

        $data = array("notice" => $notice, "message" => $msg);
        echo json_encode($data);
    }

    public function addCharacters()
    {
        $this->load->model('ValidateRequest');
        $result = $this->ValidateRequest->validateAPI($this->keyid, $this->vcode);

        if($result) {
            $notice = "error";
            $msg = $result;
            $data = array("notice" => $notice, "message" => $msg);
            echo json_encode($data);
        } else {
            $this->load->model('Register_model');
            $characters = $this->Register_model->getCharacters($this->keyid, $this->vcode);
            echo json_encode($characters);
            //proceed
        }
    }

    public function addCharactersStep($apikey, $vcode, $char1 = null, $char2 = null, $char3 = null)
    {
        $chars = array();

        if ($char1) {
            array_push($chars, $char1);
        }

        if ($char2) {
            array_push($chars, $char2);
        }

        if ($char3) {
            array_push($chars, $char3);
        }

        if(count($chars) != 0) {
            $this->load->model('register_model');
            if($this->register_model->verifyCharacters($chars, $apikey, $vcode)) {
                //add characters
                $this->ApiKeyManagement_model->addCharacters($chars, $apikey, $vcode, $this->session->iduser);
                

            } else {
                $notice = "error";
                $msg = Msg::CHARACTER_ACCOUNT_MISMATCH;
            }


        } else {
            $notice = "error";
            $msg = Msg::NO_CHARACTER_SELECTED;
        }

        $data = array("notice" => $notice, "message" => $msg);
        echo json_encode($data);
    }
}
