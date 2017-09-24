<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Profit_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'profit';
    protected $alias      = 'p';
    protected $identifier = 'idprofit';
    protected $fields     = [
        'idprofit',
        'transaction_idbuy_buy',
        'transaction_idbuy_sell',
        'profit_unit',
        'timestamp_buy',
        'timestamp_sell',
        'characters_eve_idcharacters_IN',
        'characters_eve_idcharacters_OUT',
        'quantity_profit',
    ];

    protected function parseOptions(array $options = [])
    {
        if (isset($options['sum'])) {
            $this->db->select('sum(profit_unit * quantity_profit) as profit');
        }
        return parent::parseOptions($options);
    }

    public function countAllProfits()
    {
        $options = [
            'sum' => 1,
        ];
        return parent::getOne($options);
    }
}
