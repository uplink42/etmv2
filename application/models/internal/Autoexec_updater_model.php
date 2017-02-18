<?php declare (strict_types = 1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Autoexec_updater_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all registered users
     * @return array
     */
    public function getAllUsers() : array
    {
        $this->db->select('username, iduser');
        $this->db->from('user');
        $this->db->order_by('iduser', 'desc');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
}
