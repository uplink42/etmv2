<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Stats_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'stats';
    protected $alias      = 's';
    protected $identifier = 'id';
    protected $fields     = [
        'id',
        'profit',
        'transactions',
        'api_keys',
        'characters',
        'timestamp',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }

    public function saveStats(array $data = [])
    {
        return parent::insert($data);
    }

    public function getStats()
    {
        $options = [
            'limit' => 1,
            'order_by' => 'id',
            'order_dir' => 'desc',
        ];
        return parent::getOne($options);
    }
}