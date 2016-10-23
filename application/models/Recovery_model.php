<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Recovery_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserByEmail(string $email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('user');
        $result = $query->row();

        return $result;
    }
}
