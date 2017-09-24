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
    protected $fields     = [
        'idaggr',
        'user_iduser',
        'character_eve_idcharacter',
    ];

    protected function parseOptions(array $options = [])
    {
        $this->db->select(
            ['c.eve_idcharacter as id_character', 
             'a.user_iduser as id_user', 
             'c.name as name', 
             'c.api_apikey as apikey',
             'ap.vcode as vcode',
        ]);

        $this->db->join('characters c', 'c.eve_idcharacter = ' . $this->alias . '.character_eve_idcharacter');
        $this->db->join('api ap', 'ap.apikey = c.api_apikey');

        return parent::parseOptions($options);
    }
}