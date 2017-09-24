<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Changelog_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'changelog';
    protected $alias      = 'c';
    protected $identifier = 'idchangelog';
    protected $fields     = [
        'idchangelog',
        'date',
        'content',
    ];

    protected function parseOptions(array $options = [])
    {
        return parent::parseOptions($options);
    }
}