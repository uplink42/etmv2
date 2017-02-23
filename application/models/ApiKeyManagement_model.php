<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;

class ApiKeyManagement_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get current list of characters and keys for this user
     * @param  int    $id_user 
     * @return array          
     */
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

    /**
     * Remove all character associations for a user
     * @param  int    $id_character 
     * @return bool               
     */
    public function removeCharacterProcess(int $id_character) : bool
    {
        $this->db->where('character_eve_idcharacter', $id_character);
        $this->db->delete('aggr');

        if($this->db->affected_rows() != 0) {
            return true;
        }
        return false;
    }

    /**
     * Add a set of characters to an account
     * @param array  $chars   
     * @param int    $apikey  
     * @param string $vcode   
     * @param int    $id_user 
     * @return string error or success
     */
    public function addCharacters(array $chars, int $apikey, string $vcode, int $id_user) 
    {
        $this->load->model('Register_model');
        $this->db->trans_start();

        $data2 = array(
            "apikey" => $apikey,
            "vcode"  => $vcode,
        );

        $this->db->query("INSERT INTO api(apikey, vcode) VALUES ('$apikey', '$vcode') ON DUPLICATE KEY UPDATE apikey='$apikey', vcode='$vcode'");

        foreach ($chars as $row) {
            $character_id = (int) $row;
            //check if character already exists in db
            if ($this->Register_model->checkCharacterExists($character_id)) {
                $this->db->trans_rollback();
                $error = Msg::CHARACTER_ALREADY_TAKEN;
                return $error;
            }

            if ($this->getCharacterCount($id_user) > 19) {
                return Msg::CHARACTER_LIMIT_EXCEEDED;
            }

            $pheal            = new Pheal($apikey, $vcode, "char"); //fetch character name
            $result           = $pheal->CharacterSheet(array("characterID" => $character_id));
            $character_name   = $this->security->xss_clean($result->name);
            $eve_idcharacter  = $character_id;
            $name             = $this->db->escape($character_name);
            $balance          = 0;
            $api_apikey       = $apikey;
            $networth         = 0;
            $escrow           = 0;
            $total_sell       = 0;
            $broker_relations = 0;
            $accounting       = 0;

            $this->db->query("INSERT INTO characters
                (eve_idcharacter, name, balance, api_apikey, networth, escrow, total_sell, broker_relations, accounting)
                  VALUES ('$eve_idcharacter', " . $name . ", '$balance', '$api_apikey', '$networth', '$escrow', '$total_sell', '$broker_relations', '$accounting')
                      ON DUPLICATE KEY UPDATE eve_idcharacter = '$eve_idcharacter', name=" . $name . ", api_apikey = '$api_apikey', networth='$networth',
                          escrow='$escrow', total_sell='$total_sell', broker_relations='$broker_relations', accounting='$accounting'");

            $data4 = array(
                "idaggr"                    => null,
                "user_iduser"               => $id_user,
                "character_eve_idcharacter" => $character_id,
            );

            $this->db->insert('aggr', $data4);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return Msg::DB_ERROR;
        } 
        return "ok";
    }

    /**
     * Count how many characters a user has associated
     * @param  int    $id_user 
     * @return string          
     */
    private function getCharacterCount(int $id_user) : string
    {
        $this->db->select('count(character_eve_idcharacter) as count');
        $this->db->from('v_user_characters');
        $this->db->where('iduser', $id_user);
        $query = $this->db->get();
        $result = $query->row()->count;

        return $result;
    }
}
