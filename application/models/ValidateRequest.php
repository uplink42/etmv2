<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ValidateRequest extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function checkCharacterBelong($character_id, $user_id, $json = null)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');

        if ($query->num_rows() != 0) {
            return true;
        } else if ($json) {
            echo Msg::INVALID_REQUEST;
        } else {
            return false;
        }

    }

}
