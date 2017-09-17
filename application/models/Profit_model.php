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
