<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $aggregate;
    protected $page;
    protected $idUser;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'user');
        $this->load->model('Aggr_model', 'aggr');
        $this->load->model('Characters_model', 'characters');

        $this->idUser = (int) $this->etmsession->get('iduser');

        $this->load->helper('msg_helper');
        $this->load->helper('log_helper');
        $this->load->helper('validation_helper');

        $this->load->library('etmsession');
        $this->load->library('twig');

        if ($this->config->item('maintenance') == true) {
            redirect('internal/maintenance');
        }
    }

    private function clearMessages()
    {
        $this->etmsession->delete('msg');
        $this->etmsession->delete('notice');
    }

    private function setAggregate()
    {
        $aggr = (bool) $this->input->get('aggr');
    }

    /**
     * Enforce session validation for all private pages
     * @param  int      $character_id 
     * @param  int|null $user_id      
     * @return bool               
     */
    protected function enforce($character_id, $user_id = null, bool $isJSRequest = false): bool
    {
        // get rid of unwanted session variables
        $this->clearMessages();
        $count = $this->user->countAll(array('username' => $this->etmsession->get('username'), $this->idUser));
        if ($count < 1) {
            if (!$isJSRequest) {
                buildMessage("error", Msg::INVALID_REQUEST_SESSION);
                $data['view'] = "login/login_v";
                $data['SESSION']   = $_SESSION;
                $data['no_header'] = 1;

                $this->etmsession->delete('username');
                $this->etmsession->delete('start');
                $this->etmsession->delete('iduser');
                $this->twig->display('main/_template_v', $data);
            }
            return false;
        }

        $this->setAggregate();
        return true;
    }

    /**
     * Returns the character list for a user
     * @param  int    $user_id
     * @return array    
     */
    /*protected function getCharacterList(int $user_id) : array
    {
        $data = $this->Login_model->getCharacterList($user_id);
        return $data;
    }*/

    /**
     * Loads common view dependencies for most pages
     * @param  int    $character_id 
     * @param  int    $user_id      
     * @param  bool   $aggregate    
     * @return array           
     */
    protected function loadCommon($idCharacter, $idUser, $aggregate) : array
    {
        $chars      = [];
        $charNames  = [];

        if ($aggregate) {
            $characterList = $this->aggr->getAll(array('user_iduser' => $this->idUser));
            foreach ($characterList as $char) {
                array_push($chars, $char->name);
                array_push($charNames, $char->character_eve_idcharacter);
            }
        } else {
            array_push($chars, $idCharacter);
        }

        $data['email']           = $this->etmsession->get('email');
        $data['username']        = $this->etmsession->get('username');
        $data['chars']           = $chars;
        $data['aggregate']       = $aggregate;
        $data['charNames']       = $charNames;
        $data['character_list']  = $characterList;
        $data['character_name']  = $this->characters->getOne(array('eve_idcharacter' => $idCharacter))->name;
        $data['character_id']    = $idCharacter;
        $data['HASH_CACHE']      = HASH_CACHE; // twig can't access CI constants
        $data['SESSION']         = $_SESSION;  // nor session variables
        
        $data['selector']        = $this->buildSelector();
        return $data;
    }


    /**
     * Build the character selector dropdown with options
     * @return array
     */
    private function buildSelector(): array
    {
        switch ($this->page) {
            case ('dashboard'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('transactions'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('profits'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('statistics'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('marketorders'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('contracts'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('assets'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = true;
                $data['gets']        = "sig";
                $data['gets']        = false;
                break;

            case ('networthtracker'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('tradesimulator'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('stocklists'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('traderoutes'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('citadeltax'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('settings'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('apikeymanagement'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('marketexplorer'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

        }

        $data['page'] = $this->page;
        return $data;
    }

    protected function buildData(int $idCharacter, bool $aggr, string $callback, string $model, array $configs)
    {
        $msg = Msg::INVALID_REQUEST;
        $notice = "error";
        
        if ($this->enforce($idCharacter, $this->idUser, true)) {
            $chars = [];
            if ($aggregate) {
                $characterList = $this->aggr->getAll(array('user_iduser' => $this->idUser));
                foreach ($characterList as $char) {
                    array_push($chars, $char->name);
                }
            } else {
                array_push($chars, $idCharacter);
            }

            if ($chars) {
                $configs['chars'] = $chars;
                $this->load->model($model);
                return $this->{$model}->$callback($configs);
            }
        }

        return json_encode(array("notice" => $notice, "message" => $msg));
    }
}
