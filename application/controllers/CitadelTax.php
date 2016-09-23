<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CitadelTax extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->library('session');
        $this->page = "CitadelTax";

        
    }

    public function index($character_id)
    {
        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars = $data['chars'];

            $data['selected'] = "assets";

            $data['view']           = 'main/citadeltax_v';
            $this->load->view('main/_template_v', $data);
        }
    }


}
