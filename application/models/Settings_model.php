<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings_model extends CI_Model
{
	public function getEmail(int $id_user) : stdClass
	{
		$this->db->select('email');
		$this->db->where('iduser', $id_user);
		$query = $this->db->get('user');
		$result = $query->row();

		return $result;
	}


	public function changeEmail(int $id_user, string $email) : bool
	{
		$data = array("email" => $email);
		$this->db->where('iduser', $id_user);
		$this->db->update('user', $data);
		
		if($this->db->affected_rows() != 0) {
			return true;
		}

		return false;
	}

	public function getReportSelection(int $id_user) : stdClass
	{
		$this->db->select('reports');
		$this->db->where('iduser', $id_user);
		$query = $this->db->get('user');
		$result = $query->row();
		
		return $result;
	}

	public function changeReports(int $id_user, string $value) : bool
	{
		$data = ["reports" => $value];

		$this->db->trans_start();
		$this->db->where('iduser', $id_user);
		$this->db->update('user', $data);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE) {
			return false;
		}
		return true;
	}

	public function changePassword(int $id_user, string $password) : bool
	{
		$this->load->model('common/Auth');
		$hashed = $this->Auth->createHashedPassword($password);

		$data = ["password" => $hashed['password'],
		         "salt"     => $hashed['salt']];

		$this->db->trans_start();
		$this->db->where('iduser', $id_user);     
		$this->db->update('user', $data);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE) {
			return false;
		}
		return true;
	}
    

}
