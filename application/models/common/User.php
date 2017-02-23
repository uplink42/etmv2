<?php
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

    /**
     * Returns a list of all users
     * @return array 
     */
    public function getUsers(): array
    {
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    /**
     * Returns a list of users filtered by report interval
     * @param  string $interval 
     * @return array           
     */
    public function getUsersByReports(string $interval): array
    {
        $this->db->select('username, iduser');
        $this->db->where('reports', $interval);
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    /**
     * Returns a username
     * @param  int    $user_id 
     * @return string          
     */
    public function getUsername(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->username;

        return $result;
    }

    /**
     * Returns a user's email
     * @param  int    $user_id 
     * @return string          
     */
    public function getUserEmail(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->email;

        return $result;
    }
}
