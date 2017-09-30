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

    public function getNewInfo(array $chars)
    {
        $info = [
            'contracts'    => 0,
            'orders'       => 0,
            'transactions' => 0,
        ];

        foreach ($chars as $char) {
            $data = $this->getOne(array('characters_eve_idcharacters' => $char));
            $info['contracts'] += $data->contracts;
            $info['orders'] += $data->orders;
            $info['transactions'] += $data->transactions;
        }

        return $info;
    }
}
