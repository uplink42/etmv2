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

    protected function parseOptions(array $options = [])
    {
    	$this->db->from($this->table . ' ' . $this->alias);
    	if (isset($options['order_by']) && isset($options['order_dir'])) {
    		$this->db->order_by("{$this->alias}.{$options['order_by']}", $options['order_dir']);
    	}

    	return $this->db->get('');
    }

    protected function insert(array $data = [])
    {
    	if ($this->db->insert($this->table, $data)) {
    		return $this->db->insert_id();
    	}

    	return false;
    }

    protected function update($id, array $data = [])
    {
    	$this->db->where($this->identifier, $id);
    	if ($this->db->update($this->table, $data)) {
    		return $this->db->affected_rows();
    	}

    	return false;
    }

    protected function delete($id)
    {
    	$this->db->where($this->identifier, $id);
    	if ($this->db->delete($this->table)) {
    		return $this->db->affected_rows();
    	}

    	return false;
    }

    protected function getAll(array $options = []) : array
    {
    	return $this->parseOptions($options)->result();
    }

    protected function getOne(array $options = []) : stdClass
    {
    	return $this->parseOptions($options)->row();
    }

    public function countAll(array $options = [])
    {
    	return count(self::getAll($options));
    }
}