<?php
ini_set('max_execution_time', '300');

defined('BASEPATH') or exit('No direct script access allowed');

final class Updater extends CI_Controller
{
    private $user_id;
    private $clear;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
        $this->load->library('etmsession');
        $this->load->model('common/Msg');
        $this->load->model('common/Log');
        $this->load->library('twig');
        $this->load->model('common/ValidateRequest');
        $this->user_id = (int) $this->etmsession->get('iduser');
    }

    /**
     * Loads the updater page
     * @return void
     */
    public function index()
    {
        $username = $this->etmsession->get('username');
        if (empty($username)) {
            redirect ('main/login');
            return;
        }

        $view = 'login/select_v';
        $this->load->model('Updater_model');
        try {
            $this->Updater_model->init($username);
        } catch (Throwable $e) {
            $this->Updater_model->release($username);
            log_message('error', $e->getMessage());
            return;
        }
        
        // check if API server is up
        if (!$this->ValidateRequest->testEndpoint()) {
            $this->removeDirectory(FILESTORAGE . 'public/public/server');
            // forward user to offline mode
            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
            $this->displayResultTable($username);
            // check if user is already updating
        } else {
            if ($this->Updater_model->isLocked($username)) {
                $this->displayResultTable($username);
            } else {
                // check if user has any keys
                $keys = $this->Updater_model->getKeys($username);
                if (count($keys) == 0) {
                    log_message('error', $username . ' has no keys');
                    // no keys, so prompt for new one
                    $this->askForKey();
                    return;
                } else {
                    // validate existing keys
                    $keys_status = $this->Updater_model->processAPIKeys($keys, $username);
                    if (!$keys_status) {
                        log_message('error', 'Unable to connect to verify key status');
                        // unable to connect to verify keys
                        buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                        $this->displayResultTable($username);
                        return;
                    } 

                    foreach($keys_status as $key => $val) {
                        $invalid_keys = [];
                        if ($val < 1) {
                            array_push($invalid_keys, $key);
                        }

                        if (count($invalid_keys) > 0) {
                            log_message('error', 'Invalid Keys detected');
                            // invalid key
                            buildMessage('error', Msg::OFFLINE_MODE_NOTICE_KEY . ' ' . implode($invalid_keys, ','));
                            $this->displayResultTable($username);
                            return;
                        }
                    }

                    // begin update
                    $this->Updater_model->lock($username);
                    try {
                        $result_iterate = $this->Updater_model->iterateAccountCharacters();
                        if (!$result_iterate) {
                            log_message('error', $username . ' iterate failed');
                            // transaction failed for some reason
                            // forward user to offline mode
                            buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                            $this->displayResultTable($username);
                        } else {
                            // if we arrived here, that means nothing went wrong (yet)
                            $this->db->trans_start();
                            $this->load->model('Updater_profit_model', 'profits');
                            $this->profits->beginProfitCalculation($username);
                            // update totals and history
                            $this->Updater_model->updateTotals($username);
                            $this->db->trans_complete();

                            if ($this->db->trans_status() === false) {
                                // something went wrong while calculating profits, abort
                                buildMessage('error', Msg::DB_ERROR);
                                $data['view']      = "login/login_v";
                                $data['no_header'] = 1;
                                $this->twig->display('main/_template_v', $data);
                                return;
                            } else {
                                // successfully updated
                                buildMessage('success', Msg::UPDATE_SUCCESS);
                                $this->displayResultTable($username);
                                $this->Updater_model->release($username);
                            }
                        }
                    } catch (Throwable $e) {
                        //if an exception happens during update (this is a bug on Eve's API)
                        log_message('error', $e->getMessage());
                        // cache is now corrupted for 24 hours, remove cached data
                        $problematicKeys = $this->Updater_model->getAPIKeys($this->user_id);
                        $this->Log->addEntry('clear', $this->user_id);

                        if (true) {
                            // todo: check error code?
                            foreach ($problematicKeys as $row) {
                                log_message('error', $username . ' deleting cache folder');
                                $key = $row->key;
                                $dir = FILESTORAGE . $key;
                                $this->removeDirectory($dir);
                                // release the lock
                                $this->Updater_model->release($username);

                                // forward user to offline mode
                                buildMessage('error', Msg::OFFLINE_MODE_NOTICE);
                                $this->displayResultTable($username);
                            }
                        } else {
                            $this->etmsession->delete('username');
                            $this->etmsession->delete('start');
                            $this->etmsession->delete('iduser');
                            $this->twig->display('main/_template_v', $data);
                        }
                    }
                }
            }
            $this->Updater_model->release($username);
        }
    }

    /**
     * Remove a cache directory
     * @param  string $path 
     * @return void      
     */
    private function removeDirectory(string $path)
    {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                is_dir($file) ? $this->removeDirectory($file) : unlink($file);
            }
            rmdir($path);
        }
    }

    /**
     * Load the user result table after updating
     * @param  string $username 
     * @return void         
     */
    private function displayResultTable(string $username)
    {
        $table = $this->Updater_model->resultTable($username);
        $this->Updater_model->release($username);
        $this->Log->addEntry('update', $this->user_id);
        $this->Log->addLogin($this->user_id);

        $data['cl']        = $this->Updater_model->getChangeLog();
        $data['cl_recent'] = $this->Updater_model->getChangeLog(true);
        $data['table']     = array($table);
        $data['view']      = "login/select_v";
        $data['SESSION']   = $_SESSION; // not part of MY_Controller
        $data['no_header'] = 1;

        // finally, load the next page
        $this->twig->display('main/_template_v', $data);
    }

    /**
     * Loads the "New api key required" page
     * @return void
     */
    private function askForKey()
    {
        $data['view']      = "login/select_nocharacter_v";
        $data['no_header'] = 1;
        buildMessage('error', Msg::LOGIN_NO_CHARS);
        $data['SESSION']   = $_SESSION; // not part of MY_Controller
        $this->twig->display('main/_template_v', $data);
    }
}
