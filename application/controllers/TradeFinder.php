<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class TradeFinder extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->page = "tradefinder";
    }


    /**
     * Loads the trade finder javascript app
     * @param  int    $character_id 
     * @return void               
     */
    public function index($character_id) : void
    {
        if ($this->enforce($character_id, $this->user_id)) {
            $this->Log->addEntry("visit " . $this->page, $this->user_id);
            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['market']   = true;
            $data['selected'] = "tradefinder";
            $data['view']     = 'main/tradefinder_v';

            $data['layout']['page_title']     = "Trade Finder";
            $data['layout']['icon']           = "pe-7s-gleam";
            $data['layout']['page_aggregate'] = false;
            $this->twig->display('main/_template_v', $data);
        }
    }
}
