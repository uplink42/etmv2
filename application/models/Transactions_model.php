<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Transactions_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'transaction';
    protected $alias      = 't';
    protected $identifier = 'idbuy';
    protected $fields     = [
        'idbuy',
        'time',
        'quantity',
        'price_unit',
        'price_total',
        'transaction_type',
        'character_eve_idcharacter',
        'station_eve_idstation',
        'item_eve_iditem',
        'transkey',
        'client',
        'remaining',
    ];

    protected function parseOptions(array $options = [])
    {
        if (isset($options['id_user'])) {
            $this->db->join('aggr a', $this->alias . '.character_eve_idcharacter = a.character_eve_idcharacter');
            $this->db->where('a.user_iduser', $options['id_user']);
        }

        if (isset($options['stack'])) {
            $this->db->select(
                'i.name as item_name,
                i.eve_iditem as item_eve_iditem,
                s.name as station_name,
                t.station_eve_idstation as station_id,
                c.eve_idcharacter as character_id,
                c.name as character_name,
                t.idbuy as idbuy,
                t.time as time,
                t.quantity as quantity,
                t.price_unit as price_unit,
                t.remaining as remaining');

            $this->db->join('characters c', $this->alias . '.character_eve_idcharacter = c.eve_idcharacter');
            $this->db->join('station s', $this->alias . '.station_eve_idstation = s.eve_idstation', 'left');
            $this->db->join('item i', $this->alias . '.item_eve_iditem = i.eve_iditem', 'left');
        }

        if (isset($options['latest'])) {
            $this->db->select('COALESCE(max(' . $this->alias . '.transkey),0) AS val');
            $this->db->where($this->alias . '.character_eve_idcharacter', $options['latest']);
        }

        if (isset($options['sum'])) {
            $this->db->select('coalesce(sum(price_total),0) as sum');
        }

        if (isset($options['date'])) {
            $this->db->where('date(time)', $options['date']);
        }

        return parent::parseOptions($options);
    }

    public function getLatestTransaction($idCharacter)
    {
        $options = array('latest' => $idCharacter);
        return parent::getOne($options);
    }

    public function batchInsert($transactions)
    {
        batch_ignore("transaction",
            array('idbuy',
                'time',
                'quantity',
                'price_unit',
                'price_total',
                'transaction_type',
                'character_eve_idcharacter',
                'station_eve_idstation',
                'item_eve_iditem',
                'transkey',
                'client',
                'remaining'),
            $transactions);
    }

    public function getStack($idCharacter = null, string $type, int $idUser)
    {
        $options['stack'] = 1;
        if (empty($idCharacter)) {
            $options['id_user'] = $idUser;
        } else {
            $options['character_eve_idcharacter'] = $idCharacter;
        }

        return parent::getAll($options, true);
    }
}