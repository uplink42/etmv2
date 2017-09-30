<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class History_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'history';
    protected $alias      = 'h';
    protected $identifier = 'idhistory';
    protected $fields     = [
        'characters_eve_idcharacters',
        'date',
        'total_buy',
        'total_sell',
        'total_profit',
        'margin',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        if (isset($options['sum'])) {
            $this->db->select('coalesce(sum(total_profit),0) as sum');
        }

        if (isset($options['characters_eve_idcharacters_IN'])) {
            $this->db->where_in($this->alias . '.characters_eve_idcharacters', $options['characters_eve_idcharacters_IN']);
        }

        if (isset($options['date_gte_7_days'])) {
            $this->db->where($this->alias . ".date>= (now() - INTERVAL 7 DAY)");
        }

        if (isset($options['group_by'])) {
            $this->db->group_by($options['group_by']);
        }

        return parent::parseOptions($options);
    }

    public function getWeekProfits(array $chars)
    {
        if (count($chars) < 1) {
            return false;
        }

        $options = [
            'characters_eve_idcharacters_IN' => $chars,
            'date_gte_7_days'                => 1,
            'order_by'                       => 'date',
            'order_dir'                      => 'asc',
            'group_by'                       => 'date',
        ];

        $select = array('total_profit');
        return parent::getAll($options, $select);
    }

    public function getWeeklySum(array $chars)
    {
        $options = [
            'sum' => 1,
            'characters_eve_idcharacters_IN' => $chars,
            'date_gte_7_days' => 1,
            'order_by' => 'date',
            'order_dir' => 'asc',
        ];

        return parent::getOne($options);
    }

    public function getDailySum(array $chars)
    {
        $options = [
            'sum' => 1,
            'characters_eve_idcharacters_IN' => $chars,
            'date_gte_1_days' => 1,
            'order_by' => 'date',
            'order_dir' => 'asc',
        ];

        return parent::getOne($options);
    }
}
