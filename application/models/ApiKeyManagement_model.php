<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ApiKeyManagement_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getCharacterList(int $id_user) : array
    {
        $this->db->select('a.apikey as api, c.eve_idcharacter as charid, c.name as name');
        $this->db->from('api a');
        $this->db->join('characters c', 'c.api_apikey = a.apikey');
        $this->db->join('aggr ag', 'ag.character_eve_idcharacter = c.eve_idcharacter');
        $this->db->join('user u', 'u.iduser = ag.user_iduser');
        $this->db->where('u.iduser', $id_user);
        $query = $this->db->get('');

        $result = $query->result_array();
        return $result;
    }

    public function removeCharacterProcess(int $id_character) : bool
    {
        $this->db->where('character_eve_idcharacter', $id_character);
        $this->db->delete('aggr');

        if($this->db->affected_rows() != 0) {
            return true;
        }

        return false;
    }

    public function addCharacters(string $chars, int $apikey, string $vcode, int $id_user) 
    {

    }
}
