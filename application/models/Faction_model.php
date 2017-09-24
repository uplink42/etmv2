<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Faction_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'faction';
    protected $alias      = 'f';
    protected $identifier = 'eve_idfaction';
    protected $fields     = [
        'eve_idfaction',
        'name',
    ];

    protected function parseOptions(array $options = [])
    {
        return parent::parseOptions($options);
    }
}