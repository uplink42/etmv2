<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Standings_faction_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'standings_faction';
    protected $alias      = 'sf';
    protected $identifier = 'idstandings_faction';
    protected $fields     = [
        'idstandings_faction',
        'characters_eve_idcharacters',
        'faction_eve_idfaction',
        'value',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }
}