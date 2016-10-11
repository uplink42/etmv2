<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Autoexec_updater_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getAllUsers()
    {
        $query = $this->db->get('user');
        $result = $query->result();

        return $result;
    }
}
