<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	function chunk_ignore($table, $keys, $data)
	{
	    $values = array();
	    foreach ($data as $array) {
	        array_push($values, "(" . implode(',', $array) . ")");
	    }
	    return "INSERT IGNORE INTO " . $table . " (" . implode(', ', $keys) . ")
			VALUES " . implode(', ', $values);
	}