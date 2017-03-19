<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a user's email
     * @param  int    $id_user 
     * @return stdClass          
     */
    public function getEmail(int $id_user): stdClass
    {
        $this->db->select('email');
        $this->db->where('iduser', $id_user);
        $query  = $this->db->get('user');
        $result = $query->row();
        return $result;
    }

    /**
     * Change a user's email. Returns true if successful.
     * @param  int    $id_user 
     * @param  string $email   
     * @return bool          
     */
    public function changeEmail(int $id_user, string $email): bool
    {
        $data = array("email" => $email);
        $this->db->where('iduser', $id_user);
        $this->db->update('user', $data);

        if ($this->db->affected_rows() != 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the user's report selection
     * @param  int    $id_user 
     * @return stdClass          
     */
    public function getReportSelection(int $id_user): stdClass
    {
        $this->db->select('reports');
        $this->db->where('iduser', $id_user);
        $query  = $this->db->get('user');
        $result = $query->row();

        return $result;
    }

    /**
     * Change the user's report selection
     * @param  int    $id_user 
     * @param  string $value   
     * @return bool          
     */
    public function changeReports(int $id_user, string $value): bool
    {
        $data = ["reports" => $value];
        $this->db->trans_start();
        $this->db->where('iduser', $id_user);
        $this->db->update('user', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }
        return true;
    }

    /**
     * Change the user's password. Returns true if successful
     * @param  int    $id_user 
     * @param  string $password 
     * @return bool           
     */
    public function changePassword(int $id_user, string $password): bool
    {
        $this->load->model('common/Auth');
        $hashed = $this->Auth->createHashedPassword($password);

        $data = ["password" => $hashed['password'],
                 "salt"     => $hashed['salt']];

        $this->db->trans_start();
        $this->db->where('iduser', $id_user);
        $this->db->update('user', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }
        return true;
    }


    public function getProfitTrackingData(int $id_user)
    {
        $this->db->select('default_buy_behaviour, default_sell_behaviour, cross_character_profits, ignore_citadel_tax');
        $this->db->where('iduser', $id_user);
        $query = $this->db->get('user');
        $result = $query->row();

        return $result;
    }


    public function changeTrackingData(int $id_user, array $data)
    {
        $this->db->trans_start();
        $this->db->where('iduser', $id_user);
        $this->db->update('user', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }
        return true;
    }
}
