<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Corporation_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'corporation';
    protected $alias      = 'c';
    protected $identifier = 'eve_idcorporation';
    protected $fields     = [
        'eve_idcorporation',
        'faction_eve_idfaction',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {

        return parent::parseOptions($options);
    }
}