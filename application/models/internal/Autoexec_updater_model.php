<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoexec_updater_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getAllUsers()
    {
        $this->db->select('username, iduser');
        $this->db->from('user');
        //$this->db->where('iduser', '3');
        $this->db->order_by('iduser', 'desc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function getSomeUsers()
    {
        $this->db->select('username, iduser');
        $this->db->from('user');
        $this->db->where('iduser', '4780');
        $this->db->order_by('iduser', 'desc');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
}
