<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class CitadelTax_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function queryCitadels($input)
    {
        $this->db->select('name as value');
        $this->db->where('eve_idstation > 1000000000000');
        $this->db->like('name', $input);
        $this->db->limit('10');
        $query  = $this->db->get('station');
        $result = $query->result_array();

        return $result;
    }

    public function getCitadelID($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('station');
        if ($query->num_rows() == 0) {
            return false;
        }

        return $query->row()->eve_idstation;
    }

    public function setTax($citadel_id, $character_id, $tax)
    {

        $data = ["station_eve_idstation" => $citadel_id,
            "character_eve_idcharacter"      => $character_id,
            "value"                          => $tax];

        $query = $this->db->replace('citadel_tax', $data);

        if ($query) {
            return true;
        }

        return false;
    }

    public function taxList($character_id)
    {
        $this->db->select('s.name, t.value, t.idcitadel_tax');
        $this->db->from('citadel_tax t');
        $this->db->join('station s', 's.eve_idstation = t.station_eve_idstation');
        $this->db->where('t.character_eve_idcharacter', $character_id);
        $query  = $this->db->get('');
        $result = $query->result_array();

        return $result;
    }


    public function removeTax($tax_id)
    {
        $this->db->where('idcitadel_tax', $tax_id);
        $this->db->delete('citadel_tax');

        if ($this->db->affected_rows() != 0) {
            return true;
        }
        return false;
    }

}
