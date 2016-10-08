<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Log extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addEntry($type, $id_user)
    {
        $data = ["type"        => $type,
        	     "user_iduser" => $id_user];

      	$this->db->insert('log', $data);
    }	




    
}
