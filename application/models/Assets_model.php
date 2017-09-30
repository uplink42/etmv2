<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Assets_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'assets';
    protected $alias      = 'a';
    protected $identifier = 'idassets';
    protected $fields     = [
        'idassets',
        'characters_eve_idcharacters',
        'item_eve_iditem',
        'quantity',
        'locationID',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        if (isset($options['sum_character'])) {
            $this->db->select('coalesce(SUM(' . $this->alias . '.quantity * ipd.price_evecentral),0) AS grand_total');
            $this->db->join('item_price_data ipd', 'ipd.item_eve_iditem = ' . $this->alias . '.item_eve_iditem');
            $this->db->where($this->alias . '.characters_eve_idcharacters', $options['sum_character']);
        }

        return parent::parseOptions($options);
    }

    public function batchInsert($assets)
    {
        batch("assets",
            array('idassets',
                'characters_eve_idcharacters',
                'item_eve_iditem',
                'quantity',
                'locationID'),
            $assets);
    }

    public function getNetworth($idCharacter)
    {
        $options = array('sum_character' => $idCharacter);
        return parent::getOne($options);
    }
}