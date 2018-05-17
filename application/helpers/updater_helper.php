<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class UpdaterHelper
{
	private $ci;

	public function __construct()
	{
		$this->ci = &get_instance();
        $this->ci->load->config('esi_config');
		$this->ci->load->model('User_model', 'user');
	}

	/**
     * Get current XML API status
     * @return bool
     */
    public function testEndpoint(): bool
    {
        try {
            $authentication = new \Seat\Eseye\Containers\EsiAuthentication([
                'client_id'     => $this->ci->config->item('esi_client_id'),
                'secret'        => $this->ci->config->item('esi_secret'),
            ]);
            $esi = new \Seat\Eseye\Eseye();
            $serverStatus = $esi->invoke('get', '/status/', []);

            if (!is_numeric($serverStatus->players)) {
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