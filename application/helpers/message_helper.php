<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

	/**
	 * Builds a toastr notification and loads it into a view
	 * @param  [type] $type    
	 * @param  [type] $message 
	 * @param  [type] $view    
	 * @return void        
	 */
	function buildMessage(string $type, string $message)
	{
		$CI = &get_instance();
	    $CI->load->library('twig');
	    $CI->load->library('etmsession');
	    $data = ['notice' => $type, 'msg' => $message];
	    $CI->etmsession->setData($data);
	}
