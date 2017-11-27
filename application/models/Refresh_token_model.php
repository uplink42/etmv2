<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Refresh_token_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'refresh_token';
    protected $alias      = 'rt';
    protected $identifier = 'id';
    protected $fields     = [
        'id',
        'token',
    ];

    protected function parseOptions(array $options = [], array $select = [])
    {
        return parent::parseOptions($options);
    }
}