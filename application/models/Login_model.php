<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($username, $password)
    {
        $this->db->where('username', $username);
        $query_salt                             = $this->db->get('user');
        isset($query_salt->row()->salt) ? $salt = $query_salt->row()->salt : $salt = "";

        $password_salt = crypt($password, $salt);

        $this->db->where('username', $username);
        $this->db->where('password', $password_salt);
        $query_pw = $this->db->get('user');

        if ($query_pw->num_rows() == 1) {
            $row  = $query_pw->row();
            $data = array(
                'username' => $row->username,
                'password' => $row->password,
                'email' => $row->email,
            );
            $this->session->set_userdata($data);
            return true;
        }

        return false;
    }

    public function getUserData($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('user');
        return $query->row();
    }

    //checks if session is valid
    public function checkSession()
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
    public function checkCharacter($character_id, $user_id)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');
        if ($query->num_rows() == 0) {
            return false;
        }
        return $query->result();
    }

    //retrieves a list of all characters associated with an account
    public function getCharacterList($user_id)
    {
        $this->db->select('name, character_eve_idcharacter as id');
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');
        $result = $query->result();

        $chars = [];
        $char_names = [];
        foreach ($result as $row) {
            array_push($chars, $row->id);
            array_push($char_names, $row->name);
        }

        $aggr = "(".implode(",", $chars).")";
        return array("aggr" => $aggr, "char_names" => $char_names, "chars" => $chars);
    }

    //returns the character name
    public function getCharacterName($character_id)
    {
        $this->db->select('name');
        $this->db->where('eve_idcharacter', $character_id);
        $query = $this->db->get('characters');

        $result = $query->row()->name;
        return $result;
    }

}
