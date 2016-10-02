<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Nav_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getHeaderData($character_id = "", $aggr = null)
    {
        if ($aggr == null) {
            $this->db->where('eve_idcharacter', $character_id);
            $query = $this->db->get('characters');
        } else {
            $this->db->select('sum(balance) as balance, sum(networth) as networth, sum(escrow) as escrow, sum(total_sell) as total_sell');
            $this->db->where('eve_idcharacter IN ' . $aggr);
            $query = $this->db->get('characters');
        }

        $data = array(
            "balance"    => number_format($query->row()->balance / 1000000000, 1) . "b",
            "networth"   => number_format($query->row()->networth / 1000000000, 1) . "b",
            "escrow"     => number_format($query->row()->escrow / 1000000000, 1) . "b",
            "total_sell" => number_format($query->row()->total_sell / 1000000000, 1) . "b",
        );
        return $data;
    }

}
