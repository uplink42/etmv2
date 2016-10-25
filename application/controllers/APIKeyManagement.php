<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class ApiKeyManagement extends MY_Controller
{
    private $keyid;
    private $vcode;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "APIKeyManagement";
        $this->load->model('ApiKeyManagement_model');

        $this->keyid = $_REQUEST['keyid'] ?? '';
        $this->vcode = $_REQUEST['vcode'] ?? '';

        settype($this->keyid, 'int');
        settype($this->vcode, 'string');
    }

    public function index($character_id)
    {
        settype($character_id, 'int');
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];
            $data['selected'] = "apikey";

            $data['view']     = 'main/apimanagement_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function getCharacters()
    {
        $data = $this->ApiKeyManagement_model->getCharacterList($this->user_id);
        echo json_encode($data);
    }

    public function removeCharacter(int $character_id_r)
    {
        $this->load->model('ValidateRequest');
        if ($this->ValidateRequest->checkCharacterBelong($character_id_r, $this->user_id)) {
            if($this->ApiKeyManagement_model->removeCharacterProcess($character_id_r)) {
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

    public function addCharactersStep(int $apikey, string $vcode, string $char1 = null, string $char2 = null, string $char3 = null)
    {
        $chars = array();
        $char1 ? array_push($chars, $char1) : '';
        $char2 ? array_push($chars, $char2) : '';
        $char3 ? array_push($chars, $char3) : '';

        if(count($chars) != 0) {
            $this->load->model('register_model');
            if($this->register_model->verifyCharacters($chars, $apikey, $vcode)) {
                $create = $this->ApiKeyManagement_model->addCharacters($chars, $apikey, $vcode, $this->user_id);
                //add characters
                if ($create == "ok") {
                    $notice = "success";
                    $msg = Msg::CHARACTER_CREATE_SUCCESS;
                } else {
                    $notice = "error";
                    $msg = $create;
                }
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
