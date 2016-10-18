<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class ReportGenerator extends CI_Model
{
    //update all totals, apis and character data
    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
    }

    public function generateProblematicItems($chars, $interval)
    {
        
    }

}
