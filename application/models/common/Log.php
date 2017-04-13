<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Logs an event in the database
     * @param string $type    event type
     * @param int    $id_user
     * @return void
     */
    public function addEntry(string $type, int $id_user)
    {
        $data = ["type"        => $type,
                 "user_iduser" => $id_user];

        $this->db->insert('log', $data);
    }

    public function addLogin($id_user)
    {
        $this->db->where('iduser', $id_user);
        $this->db->set('login_count', 'login_count + 1', FALSE);
        $this->db->update('user');
    }
}
