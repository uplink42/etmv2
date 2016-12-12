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

    public function getStats() : array
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

        $data = array("profit"     => (int)(($profit->profit)/1000000),
            "transactions"         => $transactions->transaction,
            "keys"                 => $keys->apikey,
            "characters"           => $characters->cha);

        $interval = array("profit" => round(($profit->profit)/10),
            "transactions"         => ($transactions->transaction)/10,
            "keys"                 => ($keys->apikey)/10,
            "characters"           => ($characters->cha)/10);

        return array("data" => $data, "interval" => $interval);
        return array();
    }
}
