<?php
ini_set('mysql.connect_timeout', 3000);
ini_set('default_socket_timeout', 3000);

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
        $this->load->model('internal/Autoexec_updater_model');
        $this->load->model('Updater_model');
    }

    public function index()
    {
        $chars = $this->Autoexec_updater_model->getAllUsers();

        foreach ($chars as $row) {
            $start         = microtime(true);
            $username = $row->username;
            echo $username . "\n";

            $this->Updater_model->init($username);

            if (!$this->Updater_model->testEndpoint()) {
                echo Msg::XML_CONNECT_FAILURE;
            } else if (!$this->Updater_model->processAPIKeys($username)) {
                echo Msg::XML_CONNECT_FAILURE;
            } else {

                try {
                    //catch the API acess violation bug
                    $result_iterate = $this->Updater_model->iterateAccountCharacters();
                    if ($result_iterate == "dberror") {
                        //in case the transaction fails
                        echo Msg::DB_ERROR;
                        return;
                    }
                } catch (\Pheal\Exceptions\PhealException $e) {
                    //in case the API throws an exception
                    echo sprintf(
                        "an exception was caught! Type: %s Message: %s",
                        get_class($e),
                        $e->getMessage()
                    );

                    $problematicKeys = $this->Updater_model->getAPIKeys($username);

                    foreach ($problematicKeys as $row2) {
                        $key = $row2->key;
                        $dir = FILESTORAGE . $key;
                        $this->removeDirectory($path);
                        $this->Log->addEntry('clear', $username);
                    }
                    return;
                }

                //calculate profits
                $this->db->trans_start();
                $this->Updater_model->calculateProfits();
                //iterate
                $this->Updater_model->updateTotals();
                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    echo Msg::DB_ERROR;
                    return;
                }
            }

            $finish = microtime(true);
            echo number_format($finish - $start, 2) . " for username " . $username . "\n";
        }
    }

    private function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
}
