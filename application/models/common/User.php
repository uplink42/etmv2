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

    public function getUsers(): array
    {
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    public function getUsersByReports(string $interval): array
    {
        $this->db->select('username, iduser');
        $this->db->where('reports', $interval);
        $query  = $this->db->get('user');
        $result = $query->result();

        return $result;
    }

    public function getUsername(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->username;

        return $result;
    }

    public function getUserEmail(int $user_id): string
    {
        $this->db->where('iduser', $user_id);
        $query  = $this->db->get('user');
        $result = $query->row()->email;

        return $result;
    }

}
