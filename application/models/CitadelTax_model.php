<?php if (!defined('BASEPATH')) {
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
        $query = $this->db->get('station');
        $result = $query->result_array();
        
        return $result;
    }


}
