<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marketexplorer extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page = "marketexplorer";
    }

    public function index(int $character_id)
    {
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "marketexplorer";
            $data['view']     = 'main/marketexplorer_v';
            $this->load->view('main/_template_v', $data);
        }
        
    }
}
