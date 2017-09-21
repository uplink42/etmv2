<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log
{
    public static function addEntry(string $type, int $id_user)
    {
        $data = ["type"        => $type,
                 "user_iduser" => $id_user];
        $this->db->insert('log', $data);
    }
    
    public static function addLogin($id_user)
    {
        $this->db->where('iduser', $id_user);
        $this->db->set('login_count', 'login_count + 1', FALSE);
        $this->db->update('user');
    }
}