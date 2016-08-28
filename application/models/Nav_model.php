<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Nav_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getHeaderData($character_id = "")
    {
        $this->db->where('eve_idcharacter', $character_id);
        $query = $this->db->get('characters');

        $data = array(
            "balance"    => number_format($query->row()->balance / 1000000000, 1) . "b",
            "networth"   => number_format($query->row()->networth / 1000000000, 1) . "b",
            "escrow"     => number_format($query->row()->escrow / 1000000000, 1) . "b",
            "total_sell" => number_format($query->row()->total_sell / 1000000000, 1) . "b",
        );
        return $data;
    }

    public function checkCharacterBelong($character_id, $user_id)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');

        if ($query->num_rows() != 0) {
            return true;
        }
        echo "Invalid request";
    }

}
