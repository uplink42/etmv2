<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Log controller
 * Creates on-demand log entries in the database
 */
class Log extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds a log entry for a specified type
     * @param [string] $type    [log type]
     * @param [int] $id_user    [internal user id]
     */
    public function addEntry($type, $id_user)
    {
        $data = ["type"        => $type,
                 "user_iduser" => $id_user];

        $this->db->insert('log', $data);
    }

}
