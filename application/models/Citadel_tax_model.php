<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Citadel_tax_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'citadel-tax';
    protected $alias      = 'ct';
    protected $identifier = 'idcitadel_tax';
    protected $fields     = [
        'idcitadel_tax',
        'character_eve_idcharacter',
        'station_eve_idstation',
        'value',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }
}