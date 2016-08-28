<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    /*builds a toastr notification and loads it to a view
    type: error or success
    message: notification content
    view: which view to send the message to
     */
    function buildMessage($type, $message, $view) {
	$data['notice'] = $type;    
	$data['message'] = $message;
	$data['view'] = $view;
	$CI = &get_instance();
	$CI->load->view($view, $data, true);
    }
?>