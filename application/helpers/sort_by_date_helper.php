<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

	function sortByDate($a, $b)
	{
	    if ($a['date'] == $b['date']) {
	        return 0;
	    }
	    return ($a['date'] < $b['date']) ? -1 : 1;
	}