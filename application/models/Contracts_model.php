<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Contracts_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'contracts';
    protected $alias      = 'c';
    protected $identifier = 'eve_idcontracts';
    protected $fields     = [
        'eve_idcontracts',
        'issuer_id',
        'status',
        'availability',
        'type',
        'creation_date',
        'expiration_date',
        'accepted_date',
        'completed_date',
        'price',
        'reward',
        'colateral',
        'volume',
        'fromStation_eve_idstation',
        'toStation_eve_idstation',
        'characters_eve_idcharacters',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        if (isset($options['latest'])) {
            $this->db->select('COALESCE(max(' . $this->alias . '.eve_idcontracts),0) AS val');
            $this->db->where($this->alias . '.characters_eve_idcharacters', $options['latest']);
        }

        return parent::parseOptions($options);
    }

    public function getLatestContract($idCharacter)
    {
        $options = array('latest' => $idCharacter);
        return parent::getOne($options);
    }

    public function batchInsert($contracts)
    {
        batch("contracts",
            array('eve_idcontracts',
                'issuer_id',
                'acceptor_id',
                'status',
                'availability',
                'type',
                'creation_date',
                'expiration_date',
                'completed_date',
                'price',
                'reward',
                'colateral',
                'fromStation_eve_idstation',
                'toStation_eve_idstation',
                'characters_eve_idcharacters'),
            $contracts);
    }
}