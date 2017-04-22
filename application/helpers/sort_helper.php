<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

	function sortData($result, $defs) {
        if (isset($defs['order'][0])) {
            $column_idx  = $defs['order'][0]['column'];
            $column_name = $defs['columns'][$column_idx]['data'];
            if ($defs['order'][0]['dir'] == 'asc') {
                // spaceship operator + inheriting values from parent scope
                usort($result, function($a, $b) use (&$column_name) {
                    return $a->{$column_name} <=> $b->{$column_name};
                });
            } else if ($defs['order'][0]['dir'] == 'desc') {
                usort($result, function($a, $b) use (&$column_name) {
                    return $b->{$column_name} <=> $a->{$column_name};
                });
            }
        }

        return $result;
	}