<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $aggregate;
    protected $page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msg');
        $this->load->model('ValidateRequest');
        $this->load->library('session');
    }

    //logs off if either session or character request is invalid
    protected function enforce($character_id, $user_id = "")
    {
        $this->load->model('Login_model');
        if ($this->Login_model->checkSession() &&
            $this->Login_model->checkCharacter($character_id, $user_id)) {

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

    protected function getCharacterList($user_id)
    {
        $this->load->model('Login_model');
        $data = $this->Login_model->getCharacterList($user_id);
        return $data;
    }

    protected function loadViewDependencies($character_id, $user_id, $aggregate)
    {
        $chars      = [];
        $char_names = [];

        if ($aggregate == true) {
            $this->load->model('Login_model');
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

        $this->load->model('Login_model');
        $data['character_name'] = $this->Login_model->getCharacterName($character_id);
        $data['character_id']   = $character_id;

        $data['selector'] = $this->buildSelector();
        return $data;
    }

    private function buildSelector()
    {
        switch($this->page) {

            case ('Dashboard'):
                $data['hasInterval'] = true;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Transactions'):
                $data['hasInterval'] = true;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Profits'):
                $data['hasInterval'] = true;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Statistics'):
                $data['hasInterval'] = true;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('MarketOrders'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Contracts'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Assets'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = true;
                $data['gets'] = "sig";
                $data['gets'] = false;
                break;

            case ('NetworthTracker'):
                $data['hasInterval'] = true;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('TradeSimulator'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('StockLists'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('TradeRoutes'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('CitadelTax'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

            case ('Settings'):
                $data['hasInterval'] = false;
                $data['hasRegion'] = false;
                $data['gets'] = false;
                break;

        }
        
        $data['page'] = $this->page;
        return $data;
    }

    public function generateIcon($item_id)
    {
        $url = "https://image.eveonline.com/Type/".$item_id."_32.png";
        return $url;
    }

    public function injectIcons($dataset, $type = null)
    {
        //default array
        $max = count($dataset);

        if($max>0) {
            for($i=0; $i<$max; $i++) {
                if($type == "object") {
                    $dataset[$i]->url = $this->generateIcon($dataset[$i]->item_id);
                } else {
                    $dataset[$i]['url'] = $this->generateIcon($dataset[$i]['item_id']);
                }
            }
        }
        return $dataset;
    }

}
