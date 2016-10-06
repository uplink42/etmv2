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


	public function changeEmail($id_user, $email)
	{
		$data = array("email" => $email);
		$this->db->update('user', $data);
		
		if($this->db->affected_rows() != 0) {
			return true;
		}

		return false;
	}

	public function getReportSelection($id_user)
	{
		$this->db->select('reports');
		$this->db->where('iduser', $id_user);
		$query = $this->db->get('user');
		$result = $query->row();
		
		return $result;
	}

	public function changeReports($id_user, $value)
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

	public function changePassword($id_user, $password)
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
