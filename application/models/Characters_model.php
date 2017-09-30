<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Characters_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'characters';
    protected $alias      = 'c';
    protected $identifier = 'eve_idcharacter';
    protected $fields     = [
        'eve_idcharacter',
        'name',
        'balance',
        'api_apikey',
        'networth',
        'escrow',
        'total_sell',
        'broker_relations',
        'accounting',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        if (isset($options['character_eve_idcharacter'])) {
            $this->db->join('aggr a', 'a.character_eve_idcharacter = ' . $this->alias . '.eve_idcharacter');
            $this->db->where($this->alias .'.eve_idcharacter', $options['character_eve_idcharacter']);
        }

        return parent::parseOptions($options);
    }

    public function insertUpdateCharacters($configs)
    {
        extract($configs);
        $this->db->query("INSERT INTO characters
            (eve_idcharacter, name, balance, api_apikey, networth, escrow, total_sell, broker_relations, accounting)
              VALUES ('$eve_idcharacter', " . $name . ", '$balance', '$api_apikey', '$networth', '$escrow', '$total_sell', '$broker_relations', '$accounting')
                  ON DUPLICATE KEY UPDATE eve_idcharacter = '$eve_idcharacter', name=" . $name . ", api_apikey = '$api_apikey', networth='$networth',
                      escrow='$escrow', total_sell='$total_sell', broker_relations='$broker_relations', accounting='$accounting'");
    }
}