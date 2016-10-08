<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assets extends MY_Controller
{
    private $significant;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_on();
        $this->page = "Assets";

    }

    public function index($character_id)
    {
        
    }
}
