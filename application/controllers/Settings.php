<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "Settings";
    }

    public function index($character_id)
    {

        if ($this->enforce($character_id, $user_id = $this->session->iduser)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "settings";

            $data['view'] = 'main/settings_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    public function email()
    {
        $this->load->model('Settings_model');
        $data = $this->Settings_model->getEmail($this->session->iduser);

        echo json_encode(array("email" => $data));
    }
}
