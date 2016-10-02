<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Updater extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->db->cache_delete_all();
    }

    public function index()
    {
        $username = $this->session->username;
        $view     = 'login/select_v';
        $this->load->model('Updater_model');

        $this->Updater_model->init($username);

        if (!$this->Updater_model->testEndpoint()) {
            buildMessage("error", Msg::XML_CONNECT_FAILURE, $view);
        } else if (!$this->Updater_model->processAPIKeys($username)) {
            buildMessage("error", Msg::XML_CONNECT_FAILURE, $view);
        } else {

            try {
                //catch the API acess violation bug
                $result_iterate = $this->Updater_model->iterateAccountCharacters();
                if ($result_iterate == "noChars") {
                    //in case user has no characters in account
                    $data['view']      = "login/select_nocharacter_v";
                    $data['no_header'] = 1;
                    buildMessage("error", Msg::LOGIN_NO_CHARS, $data['view']);
                    $this->load->view('main/_template_v', $data);
                    return;
                } else if ($result_iterate == "dberror") {
                    //in case the transaction fails
                    buildMessage("error", Msg::DB_ERROR, "login/login_v");
                    $data['view']      = "login/login_v";
                    $data['no_header'] = 1;
                    $this->load->view('main/_template_v', $data);
                    return;
                }
            } catch (\Pheal\Exceptions\PhealException $e) {
                //in case the API throws an exception
                echo sprintf(
                    "an exception was caught! Type: %s Message: %s",
                    get_class($e),
                    $e->getMessage()
                );
                //To-do: remove cache folder
                //add log entry
            }

            //calculate profits
            $this->db->trans_start();
            $this->Updater_model->calculateProfits();               
            //iterate
            $this->Updater_model->updateTotals();
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                log_message('error', 'transaction2 fail');
                buildMessage("error", Msg::DB_ERROR, "login/login_v");
                $data['view']      = "login/login_v";
                $data['no_header'] = 1;
                $this->load->view('main/_template_v', $data);
                return;
            } else {
                //transaction success, show the result table
                $table = $this->Updater_model->resultTable();

                buildMessage("success", Msg::LOGIN_SUCCESS, $view);
                $data['table']     = array($table);
                $data['view']      = "login/select_v";
                $data['no_header'] = 1;
                $this->load->view('main/_template_v', $data);
            }
        }
    }
}
