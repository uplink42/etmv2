<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings_model extends CI_Model
{
	public function getEmail($id_user)
	{
		$this->db->select('email');
		$this->db->where('iduser', $id_user);
		$query = $this->db->get('user');
		$result = $query->row();

		return $result;
	}
    

}
