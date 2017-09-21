<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'DB_model.php';

final class Characters_model extends DB_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table      = 'characters';
    protected $alias      = 'c';
    protected $identifier = 'eve_idcharacter';

    protected function parseOptions(array $options = [])
    {
        if (isset($options['character_eve_idcharacter'])) {
            $this->db->join('aggr a', 'a.character_eve_idcharacter = ' . $this->alias . '.eve_idcharacter');
            $this->db->where($this->alias .'.eve_idcharacter', $options['character_eve_idcharacter']);
        }

        return parent::parseOptions($options);
    }
}