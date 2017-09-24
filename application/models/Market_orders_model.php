<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Market_orders_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'orders';
    protected $alias      = 'o';
    protected $identifier = 'idorders';
    protected $fields     = [
        'idorders',
        'eve_item_iditem',
        'station_eve_idstation',
        'characters_eve_idcharacters',
        'price',
        'volume_remaining',
        'duration',
        'escrow',
        'type',
        'order_state',
        'order_range',
        'date',
        'transkey',
    ];

    protected function parseOptions(array $options = [])
    {
        if (isset($options['sum_character'])) {
            $this->db->select('coalesce(sum(' . $this->alias . '.volume_remaining * ipd.price_evecentral),0) AS grand_total');
            $this->db->join('item_price_data ipd', 'ipd.item_eve_iditem = ' . $this->alias . '.eve_item_iditem');
            $this->db->where('characters_eve_idcharacters', $options['sum_character']);
            $this->db->where($this->alias . '.order_state', 'open');
            $this->db->where($this->alias . '.type', 'sell');
        }

        return parent::parseOptions($options);
    }

    public function getSum($idCharacter)
    {
        $options = [
            'sum_character' => $idCharacter,
        ];

        return parent::getOne($options);
    }

    public function batchInsert($marketOrders)
    {
        batch("orders",
            array('idorders',
                'eve_item_iditem',
                'station_eve_idstation',
                'characters_eve_idcharacters',
                'price',
                'volume_remaining',
                'duration',
                'escrow',
                'type',
                'order_state',
                'order_range',
                'date',
                'transkey'),
            $marketOrders
        );
    }
}