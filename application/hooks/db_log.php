<?php
class Db_log {
 
    function __construct() {
    }
 
    // Name of function same as mentioned in Hooks Config
    function logQueries() {
        $CI = & get_instance();
        $filepath = APPPATH . 'logs/Query-log-' . date('Y-m-d') . '.php'; 
        // Creating Query Log file with today's date in application/logs folder
        $handle = fopen($filepath, "a+");                
 
        $times = $CI->db->query_times;                   
        // Get execution time of all the queries executed by controller
        foreach ($CI->db->queries as $key => $query) { 
            $sql = $query . " \n Execution Time:" . $times[$key]; 
            fwrite($handle, $sql . "\n\n");             
        }
 
        fclose($handle);
    }
}
 