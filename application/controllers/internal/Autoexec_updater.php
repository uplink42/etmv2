<?php declare(strict_types=1);
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '0');

defined('BASEPATH') or exit('No direct script access allowed');

class Autoexec_updater extends CI_Controller
{

    //update all totals, apis and character data
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
        $chars = $this->Autoexec_updater_model->getAllUsers();

        foreach ($chars as $row) {
            $start    = microtime(true);
            $username = $row->username;
            $iduser = $row->iduser;
            echo $username . "\n";

            $this->Updater_model->init($username);

            if (!$this->ValidateRequest->testEndpoint()) {
                echo Msg::XML_CONNECT_FAILURE;
            } else if (!$this->Updater_model->processAPIKeys($username)) {
                echo Msg::XML_CONNECT_FAILURE;
            } else {

                if ($this->Updater_model->isLocked($username)) {
                    log_message('error', $username . ' is locked');
                } else {
                    try {
                        $this->Updater_model->lock($username);
                        log_message('error', $username . ' locked initial');
                        //catch the API acess violation bug
                        $result_iterate = $this->Updater_model->iterateAccountCharacters();

                        if ($result_iterate == "noChars") {
                            echo Msg::LOGIN_NO_CHARS;

                        } else if ($result_iterate == "dberror") {
                            echo Msg::DB_ERROR;
                        }
                    } catch (\Pheal\Exceptions\PhealException $e) {
                        //in case the API throws an exception (usually a bug)
                        echo sprintf(
                            "an exception was caught! Type: %s Message: %s",
                            get_class($e),
                            $e->getMessage()
                        );

                        //remove cache and try again
                        //remove cache by character, not user!
                        $problematicKeys = $this->Updater_model->getAPIKeys($iduser);

                        foreach ($problematicKeys as $row) {
                            $key = $row->key;
                            $dir = FILESTORAGE . $key;
                            $this->removeDirectory($dir);
                            $this->Log->addEntry('clear', $iduser);

                            $this->Updater_model->release($username);
                            echo "API cache flushed \n";
                            log_message('error', $username . ' released errpr');
                        }
                    }
                }

                //calculate profits
                $this->db->trans_start();
                $this->Updater_model->calculateProfits();
                //totals and history
                //$this->Updater_model->updateTotals();
                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    echo Msg::DB_ERROR;
                    return;
                } else {
                    $finish = microtime(true);
                    echo number_format($finish - $start, 2) . " for username " . $username . "\n";
                    $this->Updater_model->release($username);
                    log_message('error', $username .' released');
                }
            }
        }
    }

    private function removeDirectory($path)
    {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                is_dir($file) ? $this->removeDirectory($file) : unlink($file);
            }
            rmdir($path);
            return;
        }
    }

}
