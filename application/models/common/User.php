<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    public function getUsers()
    {
    	$query = $this->db->get('user');
    	$result = $query->result();

    	return $result;
    }


}
