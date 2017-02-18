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
	function buildMessage($type, $message, $view)
	{
	    $data['notice']  = $type;
	    $data['message'] = $message;
	    $data['view']    = $view;
	    $CI              = &get_instance();
	    $CI->load->view($view, $data, true);
	}
