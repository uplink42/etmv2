<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;

class UpdaterHelper
{
	private $ci;

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model('User_model', 'user');
	}

	/**
     * Get current XML API status
     * @return bool
     */
    public function testEndpoint(): bool
    {
        try {
            $pheal    = new Pheal();
            $response = $pheal->serverScope->ServerStatus();
            if (!is_numeric($response->onlinePlayers)) {
                return false;
            }
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

	public function lock($idUser)
    {
    	$this->ci->user->update($idUser, array('updating' => 1));
    }

    public function release($idUser)
    {
    	$this->ci->user->update($idUser, array('updating' => 0));
    }

    public function isLocked($idUser) : bool
    {
    	$user = $this->ci->user->getOne(['iduser' => $idUser]);
    	if ($user->updating == '1') {
    		return true;
    	}

    	return false;
    }

    /**
     * Remove a cache directory
     * @param  string $path
     * @return void
     */
    public function removeDirectory(string $path)
    {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                is_dir($file) ? $this->removeDirectory($file) : unlink($file);
            }
            rmdir($path);
        }
    }
}