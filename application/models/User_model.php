<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class User_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('msg');
    }

    protected $table      = 'user';
    protected $alias      = 'u';
    protected $identifier = 'iduser';

    protected function parseOptions(array $options = [])
    {
        if (isset($options['iduser'])) {
            $this->db->where('iduser', $options['iduser']);
        }

        if (isset($options['username'])) {
            $this->db->where('username', $options['username']);
        }

        if (isset($options['password'])) {
            $this->db->where('password', $options['password']);
        }

        if (isset($options['email'])) {
            $this->db->where('email', $options['email']);
        }

        return parent::parseOptions($options);
    }
}