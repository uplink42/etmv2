<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apikeymanagement extends MY_Controller
{
    private $keyid;
    private $vcode;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "apikeymanagement";
        $this->load->model('ApiKeyManagement_model');

        $this->keyid = $_REQUEST['keyid'] ?? '';
        $this->vcode = $_REQUEST['vcode'] ?? '';

        settype($this->keyid, 'int');
        settype($this->vcode, 'string');
    }

    /**
     * Loads the api key management page
     * @param  string $character_id 
     * @return void               
     */
    public function index(string $character_id)
    {
        settype($character_id, 'int');
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];
            $data['selected'] = "apikey";

            $data['layout']['page_title']     = "API Key Management";
            $data['layout']['icon']           = "fa fa-users";
            $data['layout']['page_aggregate'] = false;

            $data['view']     = 'main/apimanagement_v';
            $this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Returns the character list for a user
     * @return string json
     */
    public function getCharacters()
    {
        $data = $this->ApiKeyManagement_model->getCharacterList($this->user_id);
        echo json_encode($data);
    }

    /**
     * Remove a character from an account
     * @param  int    $character_id_r 
     * @return string json                 
     */
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

    /**
     * Adds a character or set of characters to an account
     * @return string json      
     */
    public function addCharacters()
    {
        $this->load->model('common/ValidateRequest', 'validate');
        $result = $this->validate->validateAPI($this->keyid, $this->vcode);

        if($result) {
            $notice = "error";
            $msg    = $result;
            $data   = array("notice" => $notice, "message" => $msg);
            echo json_encode($data);
        } else {
            //success
            $this->load->model('Register_model');
            $characters = $this->Register_model->getCharacters($this->keyid, $this->vcode);
            echo json_encode($characters);
        }
    }

    /**
     * Add characters to account - step 2
     * @param int         $apikey 
     * @param string      $vcode  
     * @param string|null $char1  
     * @param string|null $char2  
     * @param string|null $char3 
     * @return string json
     */
    public function addCharactersStep(int $apikey, string $vcode, string $char1 = null, string $char2 = null, string $char3 = null)
    {
        $chars = array();
        $char1 ? array_push($chars, $char1) : '';
        $char2 ? array_push($chars, $char2) : '';
        $char3 ? array_push($chars, $char3) : '';

        if(count($chars) != 0) {
            $this->load->model('register_model', 'reg');
            if($this->reg->verifyCharacters($chars, $apikey, $vcode)) {
                $create_error = $this->ApiKeyManagement_model->addCharacters($chars, $apikey, $vcode, $this->user_id);
                //add characters
                if (!$create_error) {
                    $notice = "success";
                    log_message('error', Msg::CHARACTER_CREATE_SUCCESS);
                    $msg = Msg::CHARACTER_CREATE_SUCCESS;
                } else {
                    $notice = "error";
                    $msg = $create_error;
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
