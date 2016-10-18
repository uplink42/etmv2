<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    public function getUsers() : array
    {
    	$query = $this->db->get('user');
    	$result = $query->result();

    	return $result;
    }

    public function getUsersByReports(int $interval) : array
    {
        $this->db->select('username, iduser');
        $this->db->where('reports', $interval);
        $query = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    


}
