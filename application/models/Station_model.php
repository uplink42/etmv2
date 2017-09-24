<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Station_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'station';
    protected $alias      = 's';
    protected $identifier = 'eve_idstation';
    protected $fields     = [
        'eve_idstation',
        'name',
        'system_eve_idsystem',
        'corporation_eve_idcorporation',
    ];

    protected function parseOptions(array $options = [])
    {
        return parent::parseOptions($options);
    }
}