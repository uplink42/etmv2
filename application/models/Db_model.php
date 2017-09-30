<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DB_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $table; // table name
    protected $alias; // table alias
    protected $identifier; // table id column
    protected $fields; // table columns

    protected function getTable()
    {
        return $this->table;
    }

    protected function getTableAlias()
    {
        return $this->alias;
    }

    protected function getTableIdentifier()
    {
        return $this->identifier;
    }

    protected function parseOptions(array $options = [], array $select = [])
    {
        // parse selected fields
        foreach ($select as $field) {
            $this->db->select($field);
        }

        // parse table fields
        foreach ($this->fields as $field) {
            if (isset($options[$field])) {
                $this->db->where($this->alias . '.' . $field, $options[$field]);
            }
        }

        // alias own table
        $this->db->from($this->table . ' ' . $this->alias);

        // order
        if (isset($options['order_by']) && isset($options['order_dir'])) {
            $this->db->order_by($this->alias . '.' . $options['order_by'], $options['order_dir']);
        }

        // pagination
        if (isset($options['limit']) && !isset($options['skip'])) {
            $this->db->limit($options['limit']);
        }

        if (isset($options['limit']) && isset($options['skip'])) {
            $this->db->limit($options['limit'], $options['skip']);
        }

        return $this->db->get('');
    }

    public function insert(array $data = [])
    {
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    public function update($id, array $data = [])
    {
        $this->db->where($this->identifier, $id);
        if ($this->db->update($this->table, $data)) {
            return $this->db->affected_rows();
        }

        return false;
    }

    public function delete(array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($this->db->delete($this->table)) {
            return $this->db->affected_rows();
        }

        return false;
    }

    public function getAll(array $options = [], array $select = [], bool $isArray = false): array
    {
        if ($isArray) {
            return $this->parseOptions($options)->result_array();
        } else {
            return $this->parseOptions($options)->result();
        }
    }

    public function getOne(array $options = [], array $select = [])
    {
        return $this->parseOptions($options)->row();
    }

    public function countAll(array $options = [])
    {
        return count(self::getAll($options));
    }

    public function insertOrUpdate(array $options = [])
    {
        $data = $this->getOne([$this->identifier => $options[$this->identifier]]);
        if ($data) {
            $id = $data->{$this->identifier};
            return $this->update($id, $options);
        } else {
            return $this->insert($options);
        }
    }

    public function insertOrIgnore(array $options = [])
    {
        $data = $this->getOne([$this->identifier => $options[$this->identifier]]);
        if (!$data) {
            return $this->insert($options);
        }
    }
}
