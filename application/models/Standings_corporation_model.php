<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Standings_corporation_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'standings_corporation';
    protected $alias      = 'sc';
    protected $identifier = 'idstandings_corporation';
    protected $fields     = [
        'idstandings_corporation',
        'characters_eve_idcharacters',
        'corporation_eve_idcorporation',
        'value',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }
}