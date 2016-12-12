<?php
ini_set('mysql.connect_timeout', '3000');
ini_set('default_socket_timeout', '3000');
ini_set('max_execution_time', '300');

defined('BASEPATH') or exit('No direct script access allowed');

class Updater extends CI_Controller
{
    private $user_id;
    private $clear;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->library('session');
        $this->load->model('common/Msg');
        $this->load->model('common/Log');
        $this->load->model('common/ValidateRequest');
        $this->user_id = (int) $this->session->iduser;
    }

    public function index()
    {
        $count = 0;
        $username = $this->session->username;
        
        if (empty($username)) {
            redirect ('main/login');
            return;
        }

        $view     = 'login/select_v';
        $this->load->model('Updater_model');

        log_message('error', $username . ' updates');
        $this->Updater_model->init($username);

        //check if API server is up
        if (!$this->ValidateRequest->testEndpoint()) {
            buildMessage("error", Msg::XML_CONNECT_FAILURE, $view);
            //check if user is already updating
        } else {
            if ($this->Updater_model->isLocked($username)) {
                $this->displayResultTable($username);
            } else {
                //check if user has any keys
                $keys = $this->Updater_model->getKeys($username);
                if (count($keys) == 0) {
                    //no keys, so prompt for new one
                    $this->askForKey();
                    return;
                } else {
                    //validate existing keys
                    if (!$this->Updater_model->processAPIKeys($keys, $username)) {
                        //no characters left now
                        $this->askForKey();
                        return;
                    } else {
                        //begin update
                        $this->Updater_model->lock($username);
                        try {
                            $result_iterate = $this->Updater_model->iterateAccountCharacters();
                            if (!$result_iterate) {
                                //transaction failed for some reason
                                buildMessage("error", Msg::DB_ERROR, "login/login_v");
                                $data['view']      = "login/login_v";
                                $data['no_header'] = 1;
                                return;
                            } else {
                                //working as expected
                                $this->Updater_model->release($username);

                                //if we arrived here, that means nothing went wrong (yet)
                                //calculate profits
                                $this->db->trans_start();
                                $this->Updater_model->calculateProfits();
                                //totals and history
                                $this->Updater_model->updateTotals();
                                $this->db->trans_complete();

                                if ($this->db->trans_status() === false) {
                                    //something went wrong while calculating profits, abort
                                    buildMessage("error", Msg::DB_ERROR, "login/login_v");
                                    $data['view']      = "login/login_v";
                                    $data['no_header'] = 1;
                                    $this->load->view('main/_template_v', $data);
                                    return;
                                } else {
                                    $this->displayResultTable($username);
                                }
                            }
                        } catch (\Pheal\Exceptions\PhealException $e) {
                            //if an exception happens during update (this is a bug on Eve's API)
                            echo sprintf(
                                "an exception was caught! Type: %s Message: %s",
                                get_class($e),
                                $e->getMessage()
                            );
                            log_message('error', $e->getMessage());

                            //cache is now corrupted for 24 hours, remove cache and try again
                            //remove all keys just in case
                            $problematicKeys = $this->Updater_model->getAPIKeys($this->user_id);
                            log_message('error', 'delete cache ' . $this->user_id);
                            $this->Log->addEntry('clear', $this->user_id);

                            if ($count < 3) {
                                $count++;
                                log_message('error', 'delete Cache');
                                foreach ($problematicKeys as $row) {

                                    $key = $row->key;
                                    $dir = FILESTORAGE . $key;
                                    $this->removeDirectory($dir);
                                    //release the lock
                                    $this->Updater_model->release($username);

                                    $this->session->unset_userdata('username');
                                    $this->session->unset_userdata('start');
                                    $this->session->unset_userdata('iduser');
                                }
                                $this->index();
                            } else {
                                $this->session->unset_userdata('username');
                                $this->session->unset_userdata('start');
                                $this->session->unset_userdata('iduser');
                                $this->load->view('main/_template_v', $data);
                            }
                            
                        }
                    }
                }
            }
            $this->Updater_model->release($username);
        }


    }


    private function removeDirectory(string $path)
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

    private function displayResultTable(string $username)
    {
        $table = $this->Updater_model->resultTable($username);
        $this->Updater_model->release($username);
        $this->Log->addEntry('update', $this->user_id);

        //transaction success, show the result table
        //buildMessage("success", Msg::LOGIN_SUCCESS, $view);

        $data['cl']        = $this->Updater_model->getChangeLog();
        $data['cl_recent'] = $this->Updater_model->getChangeLog(true);
        $data['table']     = array($table);
        $data['view']      = "login/select_v";
        $data['no_header'] = 1;

        //finally, load the next page
        $this->load->view('main/_template_v', $data);
    }

    private function askForKey()
    {
        $data['view']      = "login/select_nocharacter_v";
        $data['no_header'] = 1;
        buildMessage("error", Msg::LOGIN_NO_CHARS, $data['view']);
        $this->load->view('main/_template_v', $data);
    }

}
