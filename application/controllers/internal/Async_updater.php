<?php
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Pheal\Core\Config;
use Pheal\Pheal;

class Async_updater extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->model('common/Log');
        $this->load->model('common/Msg');
        $this->load->model('internal/Autoexec_updater_model');
        $this->load->model('Updater_model');
        $this->load->model('common/ValidateRequest');
    }

    public function index()
    {
    	if (!$this->input->is_cli_request()) {
            die('unauthorized');
        }

        $chars = $this->Autoexec_updater_model->getAllUsers();
        foreach ($chars as $row) {
        	$username = $row->username;
            $iduser = (int) $row->iduser;

            if (!$this->ValidateRequest->testEndpoint()) {
                log_message('error', 'global update->' . Msg::XML_CONNECT_FAILURE);
                //check if user is already updating
            } else {
            	if ($this->Updater_model->isLocked($username)) {
                } else {
                	$keys = $this->Updater_model->getKeys($username);
                    if (count($keys) == 0) {
                        // ?
                    } else {
                    	if (!$this->Updater_model->processAPIKeys($keys, $username)) {
                        } else {
                        	$this->Updater_model->lock($username);
                        	exec("php /var/www/html/v2 && php index.php internal/Async_procedure index " . $iduser . " > /dev/null &");
                        }
                    }
                }
            }
        }
    }
}