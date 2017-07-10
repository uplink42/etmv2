<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Maintenance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Displays maintenance mode
     * @return void 
     */
    public function index() : void
    {
    	$this->load->view('errors/html/error_maint');
    }
}
