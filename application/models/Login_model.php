<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getUserData(string $username) : stdClass
    {
        $this->db->where('username', $username);
        $query = $this->db->get('user');
        return $query->row();
    }

    //checks if session is valid
    public function checkSession() : bool
    {
        if (empty($this->session->username) || empty($this->session->iduser)) {
            return false;
        } else {
            $this->db->where('username', $this->session->username);
            $this->db->where('iduser', $this->session->iduser);
            $query = $this->db->get('user');

            if ($query->num_rows() < 1) {
                return false;
            }
            return true;
        }
    }

    //check if the assigned character belongs to account,
    /*public function checkCharacter($character_id, $user_id)
    {
    $this->db->where('character_eve_idcharacter', $character_id);
    $this->db->where('iduser', $user_id);
    $query = $this->db->get('v_user_characters');
    if ($query->num_rows() == 0) {
    return false;
    }
    return $query->result();
    }*/

    //retrieves a list of all characters associated with an account
    public function getCharacterList(int $user_id) : array
    {
        $this->db->select('name, character_eve_idcharacter as id');
        $this->db->where('iduser', $user_id);
        $this->db->order_by('character_eve_idcharacter');
        $query  = $this->db->get('v_user_characters');
        $result = $query->result();

        $chars      = [];
        $char_names = [];
        foreach ($result as $row) {
            array_push($chars, $row->id);
            array_push($char_names, $row->name);
        }

        $aggr = "(" . implode(",", $chars) . ")";
        return array("aggr" => $aggr, "char_names" => $char_names, "chars" => $chars);
    }

    //returns the character name
    public function getCharacterName(int $character_id) : string
    {
        $this->db->select('name');
        $this->db->where('eve_idcharacter', $character_id);
        $query = $this->db->get('characters');

        $result = $query->row()->name;
        return $result;
    }

}
