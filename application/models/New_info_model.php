<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class New_info_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'new_info';
    protected $alias      = 'ni';
    protected $identifier = 'characters_eve_idcharacters';
    protected $fields     = [
        'characters_eve_idcharacters',
        'contracts',
        'orders',
        'transactions',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }
}
