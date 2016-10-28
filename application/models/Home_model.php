<?php declare(strict_types=1);
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Home_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getTransactions() : array
    {
        $this->db->select('count(idbuy) as transaction');
        $query        = $this->db->get('transaction');
        $transactions = $query->row();

        $data = array("transactions" => $transactions->transaction);
        return $data;
    }

    public function getProfits() : array
    {
        $this->db->select('sum(profit_unit * quantity_profit) as profit');
        $query  = $this->db->get('profit');
        $profit = $query->row();

        $data = array("profit" => round($profit->profit)/1000000);
        return $data;
    }

    public function getCharacters() : array
    {
        $this->db->select('count(eve_idcharacter) as cha');
        $query      = $this->db->get('characters');
        $characters = $query->row();

        $data = array("characters" => $characters->cha);
        return $data;
    }

    public function getKeys() : array
    {
        $this->db->select('count(apikey) as apikey');
        $query = $this->db->get('api');
        $keys  = $query->row();

        $data = array("keys" => $keys->apikey);
        return $data;
    }

}
