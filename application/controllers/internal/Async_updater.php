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
                log_message('error', '=== GLOBAL UPDATE === ' . Msg::XML_CONNECT_FAILURE);
                // check if user is already updating
            } else {
                // check if already updating
                if ($this->Updater_model->isLocked($username)) {
                    log_message('error', '=== GLOBAL UPDATE === ' . $username . ' is locked');
                } else {
                    // count keys
                    $keys = $this->Updater_model->getKeys($username);
                    if (count($keys) == 0) {
                        log_message('error', '=== GLOBAL UPDATE === ' . $username . ' has no keys');
                    } else {
                        // check existing keys status
                        $keys_status = $this->Updater_model->processAPIKeys($keys, $username);
                        if (!$keys_status) {
                             log_message('error', '=== GLOBAL UPDATE === ' . $username . ' has invalid keys');
                        } else {
                            // check each key if valid
                            foreach($keys_status as $key => $val) {
                                $invalid_keys = [];
                                if ($val < 1) {
                                    array_push($invalid_keys, $key);
                                }
                            }
                            if (count($invalid_keys) > 0) {
                                log_message('error', '=== GLOBAL UPDATE === ' . $username . ' has invalid keys - 2');
                            } else {
                                // no invalid keys
                                sleep(3);
                                exec("php /var/www/html/v2 && php index.php internal/Async_procedure index " . $iduser . " > /dev/null &");
                            }
                        }
                    }
                }
            }
        }
    }
}