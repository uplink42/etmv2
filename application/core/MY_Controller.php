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
        $this->load->model('common/Msg');
        $this->load->model('common/Log');
        $this->load->model('common/ValidateRequest');
        $this->load->model('Login_model');
        $this->load->library('etmsession');
        $this->load->library('twig');
        $this->user_id = (int) $this->etmsession->get('iduser');

        if ($this->config->item('maintenance') == true) {
            redirect('internal/maintenance');
        }
    }

    /**
     * Enforce session validation for all private pages
     * @param  int      $character_id 
     * @param  int|null $user_id      
     * @return bool               
     */
    protected function enforce(int $character_id, int $user_id = null, bool $isJSRequest = false): bool
    {
        $this->etmsession->delete('msg');
        $this->etmsession->delete('notice');

        if ($this->Login_model->checkSession() &&
            $this->ValidateRequest->checkCharacterBelong($character_id, $user_id)) {

            if (isset($_GET['aggr'])) {
                $aggr = $_GET['aggr'];

                if ($aggr != 1 && $aggr != 0) {
                    $this->aggregate = false;
                } else {
                    $this->aggregate = (bool) $aggr;
                }
            } else {
                $this->aggregate = false;
            }

            $this->Log->addEntry("visit " . $this->page, $user_id);
            return true;
        } else {
            if (!$isJSRequest) {
                $data['view'] = "login/login_v";
                buildMessage("error", Msg::INVALID_REQUEST_SESSION, $data['view']);
                $data['no_header'] = 1;

                $this->etmsession->delete('username');
                $this->etmsession->delete('start');
                $this->etmsession->delete('iduser');
                $this->twig->display('main/_template_v', $data);
            } 
            return false;
        }
    }

    /**
     * Returns the character list for a user
     * @param  int    $user_id
     * @return array    
     */
    protected function getCharacterList(int $user_id) : array
    {
        $data = $this->Login_model->getCharacterList($user_id);
        return $data;
    }

    /**
     * Loads common view dependencies for most pages
     * @param  int    $character_id 
     * @param  int    $user_id      
     * @param  bool   $aggregate    
     * @return array           
     */
    protected function loadViewDependencies(int $character_id, int $user_id, bool $aggregate) : array
    {
        $chars      = [];
        $char_names = [];

        if ($aggregate == true) {
            $characters = $this->Login_model->getCharacterList($user_id);

            $chars      = $characters['aggr'];
            $char_names = $characters['char_names'];
        } else {
            $chars = "(" . $character_id . ")";
        }

        $data['email']           = $this->etmsession->get('email');
        $data['username']        = $this->etmsession->get('username');
        $data['chars']           = $chars;
        $character_list          = $this->getCharacterList($this->user_id);
        $data['aggregate']       = $aggregate;
        $data['char_names']      = $char_names;
        $data['character_list']  = $character_list;
        $data['character_name']  = $this->Login_model->getCharacterName($character_id);
        $data['character_id']    = $character_id;
        $data['HASH_CACHE']      = HASH_CACHE; // twig can't access CI constants
        $data['SESSION']         = $_SESSION; // nor session variables

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

    /**
     * Get the correct icon for an item
     * @param  int    $item_id 
     * @return string      
     */
    public function generateIcon(int $item_id): string
    {
        $url = "https://image.eveonline.com/Type/" . $item_id . "_32.png";
        return $url;
    }

    /**
     * Inject icons into a result array or object
     * @param  [array|stdClass]  $dataset 
     * @param  boolean $type    
     * @return array   
     */
    public function injectIcons($dataset, $type = false) : array
    {
        $max = count($dataset);

        if ($max > 0) {
            for ($i = 0; $i < $max; $i++) {
                if ($type == "object") {
                    $dataset[$i]->url = $this->generateIcon((int) $dataset[$i]->item_id);
                } else {
                    $dataset[$i]['url'] = $this->generateIcon((int) $dataset[$i]['item_id']);
                }
            }
        }
        return $dataset;
    }
}
