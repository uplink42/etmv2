<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class EtmSession
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function setData(array $data)
    {
    	foreach($data as $key => $val) {
    		$_SESSION[$key] = $val;
    	}
    }

    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public function regenerateId($delOld = false)
    {
        session_regenerate_id($delOld);
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function deleteAll()
    {
        foreach($_SESSION as $key => $val) {
            unset($_SESSION[$key]);
        }
        session_destroy();
    }
}
