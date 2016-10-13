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
        $this->db->select('username');
        $this->db->from('user');
        $this->db->order_by('iduser', 'desc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
}
