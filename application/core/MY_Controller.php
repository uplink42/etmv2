<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Main application controller which validates and logs every page request
 * and loads commonly used variables into views
 */
class MY_Controller extends CI_Controller
{
    /**
     * Account characters tuple
     * @var [string]
     */
    protected $aggregate;

    /**
     * Current page shortcode
     * @var [string]
     */
    protected $page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
        $this->load->model('common/Log');
        $this->load->model('common/ValidateRequest');
        $this->load->model('Login_model');
        $this->load->library('session');
    }

    /**
     * Enforces user/character validation
     * Terminates session if something goes wrong
     * This method is first called by all public controllers
     * @param  [type] $character_id [eve character id]
     * @param  [type] $user_id      [internal user id]
     * @return [boolean]            [validation result]
     */
    protected function enforce($character_id, $user_id = null)
    {
        if ($this->Login_model->checkSession() &&
            $this->ValidateRequest->checkCharacterBelong($character_id, $user_id)) {

            if (isset($_GET['aggr'])) {
                $aggr = $_GET['aggr'];

                if ($aggr != 1 && $aggr != 0) {
                    $this->aggregate = 0;
                } else {
                    $this->aggregate = $aggr;
                }
            } else {
                $this->aggregate = 0;
            }

            $this->Log->addEntry("visit " . $this->page, $this->session->iduser);
            return true;
        } else {
            $data['view'] = "login/login_v";
            buildMessage("error", "Invalid session or character request", $data['view']);
            $data['no_header'] = 1;

            $this->session->unset_userdata('username');
            $this->session->unset_userdata('start');
            $this->session->unset_userdata('iduser');
            $this->load->view('main/_template_v', $data);

            return false;
        }
    }

    /**
     * Returns all characters who belong to a specified user account
     * @param  [int] $user_id  [internal user id]
     * @return [array]         [character list]
     */
    protected function getCharacterList($user_id)
    {
        $data = $this->Login_model->getCharacterList($user_id);
        return $data;
    }

    /**
     * Loads all common view variables, such as current page, session data, character list
     * and character names.
     * @param  [int] $character_id    [eve character id]
     * @param  [int] $user_id         [internal user id]
     * @param  [string] $aggregate    [account characters tuple]
     * @return [array]                [view dependencies]
     */
    protected function loadViewDependencies($character_id, $user_id, $aggregate)
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

        $data['email']          = $this->session->email;
        $data['username']       = $this->session->username;
        $data['chars']          = $chars;
        $character_list         = $this->getCharacterList($this->session->iduser);
        $data['aggregate']      = $aggregate;
        $data['char_names']     = $char_names;
        $data['character_list'] = $character_list;
        $data['character_name'] = $this->Login_model->getCharacterName($character_id);
        $data['character_id']   = $character_id;

        $data['selector'] = $this->buildSelector();
        return $data;
    }

    /**
     * Loads the required data to build each page's character selector dropdown
     * @return [array] [selector data]
     */
    private function buildSelector()
    {
        switch ($this->page) {

            case ('Dashboard'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Transactions'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Profits'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Statistics'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('MarketOrders'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Contracts'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Assets'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = true;
                $data['gets']        = "sig";
                $data['gets']        = false;
                break;

            case ('NetworthTracker'):
                $data['hasInterval'] = true;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('TradeSimulator'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('StockLists'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('TradeRoutes'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('CitadelTax'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('Settings'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

            case ('APIKeyManagement'):
                $data['hasInterval'] = false;
                $data['hasRegion']   = false;
                $data['gets']        = false;
                break;

        }

        $data['page'] = $this->page;
        return $data;
    }

    /**
     * Creates an icon url from eve's image server
     * @param  [int] $item_id  [eve item id]
     * @return [string]        [image url]
     */
    public function generateIcon($item_id)
    {
        $url = "https://image.eveonline.com/Type/" . $item_id . "_32.png";
        return $url;
    }

    /**
     * Adds a new element to result lists which contains an item's icon url
     * This is commonly used to avoid having to build urls on the views
     * Works for both arrays or objects
     * @param  [array or object] $dataset [result dataset]
     * @param  [boolean] $type            [dataset type]
     * @return [array]                    [modified dataset with item url added]
     */
    public function injectIcons($dataset, $type = false)
    {
        //default array
        $max = count($dataset);

        if ($max > 0) {
            for ($i = 0; $i < $max; $i++) {
                if ($type == "object") {
                    $dataset[$i]->url = $this->generateIcon($dataset[$i]->item_id);
                } else {
                    $dataset[$i]['url'] = $this->generateIcon($dataset[$i]['item_id']);
                }
            }
        }
        return $dataset;
    }

}
