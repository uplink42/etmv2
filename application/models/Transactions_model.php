<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transactions_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getTransactionList($chars, $interval)
    {
        $this->db->select('t.idbuy as transaction_id,
            t.time as time,
            t.quantity as quantity,
            t.price_unit as price_unit,
            t.price_total as price_total,
            t.transaction_type as type,
            c.name as character_name,
            s.name as station_name,
            t.transkey as transkey,
            t.client as client,
            i.name as item_name,
            tp.transactionID as proc');
        $this->db->from('transaction t');
        $this->db->join('characters c', 'c.eve_idcharacter = t.character_eve_idcharacter');
        $this->db->join('item i', 'i.eve_iditem = t.item_eve_iditem', 'left');
        $this->db->join('station s', 's.eve_idstation = t.station_eve_idstation', 'left');
        $this->db->join('transaction_processed tp', 'tp.transactionID = t.idbuy', 'left');
        $this->db->where('t.character_eve_idcharacter IN '. $chars);
        $this->db->where("t.time>= (now() - INTERVAL " . $interval . " DAY)");
        $query = $this->db->get();
        log_message('error', $this->db->last_query());

        $result = $query->result();
        return $result;
    }

}
