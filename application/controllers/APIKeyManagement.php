<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiKeyManagement extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "APIKeyManagement";
        $this->load->model('ApiKeyManagement_model');
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
}
