<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class MarketExplorer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->page = "marketexplorer";
    }


    /**
     * Loads the market explorer javascript app
     * @param  int    $character_id 
     * @return void               
     */
    public function index($character_id) : void
    {
        $this->Log->addEntry("visit " . $this->page, $this->user_id);
        if ($this->enforce($character_id, $this->user_id)) {
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['market']   = true;
            $data['selected'] = "marketexplorer";
            $data['view']     = 'main/marketexplorer_v';

            $data['layout']['page_title']     = "Market Explorer";
            $data['layout']['icon']           = "pe-7s-glasses";
            $data['layout']['page_aggregate'] = false;
            $this->twig->display('main/_template_v', $data);
        }
    }
}
