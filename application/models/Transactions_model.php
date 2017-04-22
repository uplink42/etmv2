<?php
if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Transactions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Datatables', 'dt');
    }

    /**
     * Returns the list of transactions for a set of characters, optionally filtered
     * by type, transaction id and interval
     * @param  string       $chars    
     * @param  int          $interval 
     * @param  int|null     $new      
     * @param  string|null  $transID  
     * @param  bool|boolean $res      
     * @return array                 
     */
    public function getTransactionList(array $configs/*, bool $res = true*/) : ?string
    {
        extract($configs);
        $this->db->start_cache();
        $this->db->select('t.idbuy as transaction_id,
            t.time as time,
            t.remaining as remaining,
            t.quantity as quantity,
            t.price_unit as price_unit,
            t.price_total as price_total,
            t.transaction_type as type,
            c.name as character_name,
            s.name as station_name,
            t.transkey as transkey,
            t.client as client,
            i.name as item_name,
            i.eve_iditem as item_id');
        $this->db->from('transaction t');
        $this->db->join('characters c', 'c.eve_idcharacter = t.character_eve_idcharacter');
        $this->db->join('item i', 'i.eve_iditem = t.item_eve_iditem', 'left');
        $this->db->join('station s', 's.eve_idstation = t.station_eve_idstation', 'left');

        if (!$transID) {
            $this->db->where('t.character_eve_idcharacter IN ' . $chars);
            $this->db->where("t.time>= (now() - INTERVAL " . $interval . " DAY)");
        }

        if (!isset($defs['order'][0])) {
            $this->db->order_by("t.time DESC");
        }
        
        if ($new > 0) {
            $this->db->limit($new);
        }
        if ($transID) {
            $this->db->where('idbuy', $transID);
        }

        $result = $this->dt->generate($defs, 'i.name', 'price_total');
        $data = json_encode(['data'            => injectIcons($result['data'], true), 
                             'draw'            => (int)$result['draw'], 
                             'recordsTotal'    => $result['max'],
                             'recordsFiltered' => $result['max'],
                             'recordsSum'      => $result['sum']]);
        return $data;
    }

    /**
     * Unlinks a transaction from a user
     * @param  [type] $transaction_id 
     * @return bool                 
     */
    public function unlinkTransaction(string $transaction_id) : bool
    {
        $this->db->select('character_eve_idcharacter as c');
        $this->db->where('idbuy', $transaction_id);
        $query        = $this->db->get('transaction');
        $character_id = $query->row()->c;

        $data = array("remaining" => 0);

        $this->db->where('idbuy', $transaction_id);
        $this->db->update('transaction', $data);

        if ($this->db->affected_rows() == 1) {
            return true;
        }
        return false;
    }
}
