<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function batch($table, $keys, $data)
    {
	$values = array();
	foreach ($data as $array) {
	    array_push($values, "(".implode(',', $array).")");
	}
	
	return "REPLACE INTO ".$table." (".implode(', ', $keys).") 
		VALUES ".implode(', ', $values);

	
    }
    
?>