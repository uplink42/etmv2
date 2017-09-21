<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Aggr_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'aggr';
    protected $alias      = 'a';
    protected $identifier = 'idaggr';

    protected function parseOptions(array $options = [])
    {
        return parent::parseOptions($options);
    }
}