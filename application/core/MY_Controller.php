<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $aggregate;
    protected $page;
    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('msg');
        $this->load->helper('log');
        $this->load->helper('validation');
        //$this->load->model('Login_model');
        $this->load->library('etmsession');
        $this->load->library('twig');
        $this->load->model('User_model', 'user');
        $this->user_id = (int) $this->etmsession->get('iduser');

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
        if (isset($this->input->get['aggr'])) {
            $aggr = $this->input->get['aggr'];

            if ($aggr != 1 && $aggr != 0) {
                $this->aggregate = false;
            } else {
                $this->aggregate = (bool) $aggr;
            }
        } else {
            $this->aggregate = false;
        }
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
        $count = $this->user->countAll(['username' => $this->etmsession->get('username'), 'iduser' => $this->etmsession->get('iduser')]);
        if ($count < 1) {
            if (!$isJSRequest) {
                $data['view'] = "login/login_v";
                buildMessage("error", Msg::INVALID_REQUEST_SESSION);
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
    /*protected function loadViewDependencies($character_id, $user_id, $aggregate) : array
    {
        $chars      = [];
        $char_names = [];

        if ($aggregate) {
            $characters = $this->Login_model->getCharacterList($user_id);
            $chars      = $characters['aggr'];
            $char_names = $characters['char_names'];
        } else {
            $chars = "(" . $character_id . ")";
        }

        $data['email']           = $this->etmsession->get('email');
        $data['username']        = $this->etmsession->get('username');
        $data['chars']           = $chars;
        $data['aggregate']       = $aggregate;
        $data['char_names']      = $char_names;
        $data['character_list']  = $this->getCharacterList($this->user_id);
        $data['character_name']  = $this->Login_model->getCharacterName($character_id);
        $data['character_id']    = $character_id;
        $data['HASH_CACHE']      = HASH_CACHE; // twig can't access CI constants
        $data['SESSION']         = $_SESSION;  // nor session variables
        
        $data['selector']        = $this->buildSelector();
        return $data;
    }*/


    /**
     * Build the character selector dropdown with options
     * @return array
     */
    /*private function buildSelector(): array
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
    }*/


    /*protected function buildData(int $character_id, bool $aggr, string $callback, string $model, array $configs)
    {
        $msg = Msg::INVALID_REQUEST;
        $notice = "error";
        if ($this->enforce($character_id, $this->user_id, true)) {
            $chars      = [];
            $char_names = [];

            if ($aggr) {
                $characters = $this->Login_model->getCharacterList($this->user_id);
                $chars      = $characters['aggr'];
            } else {
                $chars = "(" . $character_id . ")";
            }

            if ($chars) {
                $configs['chars'] = $chars;
                $this->load->model($model);
                return $this->{$model}->$callback($configs);
            }
        }
        return json_encode(array("notice" => $notice, "message" => $msg));
    }*/
}
