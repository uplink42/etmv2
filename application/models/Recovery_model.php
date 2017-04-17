<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Recovery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if a username and email exists, and returns 
     * user data if so
     * @param  string $username 
     * @param  string $email    
     * @return stdClass           
     */
    public function getUserByEmail(string $username, string $email) : ?stdClass
    {
        $this->db->where('username', $username);
        $this->db->where('email', $email);
        $query  = $this->db->get('user');
        $result = $query->row();

        return $result;
    }

    /**
     * Lookup a username by email
     * @param  string $email 
     * @return stdClass        
     */
    public function getUsernameByEmail(string $email) : ?stdClass
    {
        $this->db->select('username');
        $this->db->where('email', $email);
        $query  = $this->db->get('user');
        $result = $query->row();

        return $result;
    }
}
