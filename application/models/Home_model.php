<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Home_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the total stats for the homepage
     * @return array
     */
    public function saveStats()
    {
        //profit
        $this->db->select('sum(profit_unit * quantity_profit) as profit');
        $query  = $this->db->get('profit');
        $profit = $query->row();

        //transactions
        $this->db->select('count(idbuy) as transaction');
        $query        = $this->db->get('transaction');
        $transactions = $query->row();

        //api keys
        $this->db->select('count(apikey) as apikey');
        $query = $this->db->get('api');
        $keys  = $query->row();

        //characters
        $this->db->select('count(eve_idcharacter) as cha');
        $query      = $this->db->get('characters');
        $characters = $query->row();

        $data = array(
            "profit"       => (int)(($profit->profit)/1000000),
            "transactions" => $transactions->transaction,
            "api_keys"     => $keys->apikey,
            "characters"   => $characters->cha);

        $this->db->insert('stats', $data);
    }

    public function getStats()
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit('1');
        $query = $this->db->get('stats');
        $result = $query->row();

        return $result;
    }
}
