<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{
    private $email;
    private $password;
    private $reports;
    private $password_old;
    private $password_new1;
    private $password_new2;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->page = "settings";
        $this->load->model('Settings_model');
        $this->load->model('common/ValidateRequest');

        $this->email    = $_REQUEST['email'] ?? '';
        $this->password = $_REQUEST['password'] ?? '';
        $this->reports  = $_REQUEST['reports'] ?? '';

        $this->password_old  = $_REQUEST['password-old'] ?? '';
        $this->password_new1 = $_REQUEST['password-new1'] ?? '';
        $this->password_new2 = $_REQUEST['password-new2'] ?? '';

    }

    /**
     * Loads the user settings page
     * @param  int    $character_id 
     * @return void               
     */
    public function index(int $character_id) : void
    {
        if ($this->enforce($character_id, $this->user_id)) {

            $aggregate = $this->aggregate;
            $data      = $this->loadViewDependencies($character_id, $this->user_id, $aggregate);
            $chars     = $data['chars'];

            $data['selected'] = "settings";

            $data['view'] = 'main/settings_v';
            $this->load->view('main/_template_v', $data);
        }
    }

    /**
     * Returns the user's email
     * @return string json 
     */
    public function email() : void
    {
        $data = $this->Settings_model->getEmail($this->user_id);
        echo json_encode(array("email" => $data));
    }

    /**
     * Returns the user's reports
     * @return string json 
     */
    public function reports() : void
    {
        $result = $this->Settings_model->getReportSelection($this->user_id);
        $data   = array("data" => $result);
        echo json_encode($data);
    }

    /**
     * Changes the user's email
     * @return string json
     */
    public function changeEmail() : void
    {
        $this->load->model('common/Auth');
        if ($this->Auth->validateLogin($this->session->username, $this->password, true)) {
            if ($this->ValidateRequest->validateEmailAvailability($this->email)) {
                if ($this->Settings_model->changeEmail($this->user_id, $this->email)) {
                    $notice  = "success";
                    $message = Msg::EMAIL_CHANGE_SUCCESS;
                } else {
                    $notice  = "error";
                    $message = Msg::DB_ERROR;
                }
            } else {
                $notice  = "error";
                $message = Msg::EMAIL_ALREADY_TAKEN;
            }
        } else {
            $notice  = "error";
            $message = Msg::INVALID_LOGIN;
        }
        echo json_encode(array("notice" => $notice, "message" => $message));
    }

    /**
     * Change the user's reports
     * @return string json
     */
    public function changeReports() : void
    {
        if ($this->reports == 'none' || $this->reports == 'daily' || $this->reports == 'weekly' || $this->reports == 'monthly') {
            if ($this->Settings_model->changeReports($this->user_id, $this->reports)) {
                $notice  = "success";
                $message = Msg::REPORT_CHANGE_SUCCESS;
            } else {
                $notice  = "error";
                $message = Msg::REPORT_CHANGE_ERROR;
            }
        } else {
            $notice  = "error";
            $message = Msg::REPORT_CHANGE_ERROR;
        }

        $data = ["notice" => $notice, "message" => $message];
        echo json_encode($data);
    }

    /**
     * Change a user's password
     * @return string json
     */
    public function changePassword() : void
    {
        $this->load->model('common/Auth');
        if ($this->ValidateRequest->validateIdenticalPasswords($this->password_new1, $this->password_new2)) {
            if ($this->ValidateRequest->validatePasswordLength($this->password_new1)) {
                if ($this->Auth->validateLogin($this->session->username, $this->password_old, true)) {
                    if ($this->Settings_model->changePassword($this->user_id, $this->password_new1)) {
                        $notice = "success";
                        $msg    = Msg::CHANGE_PASSWORD_SUCCESS;
                    } else {
                        $notice = "error";
                        $msg    = Msg::CHANGE_PASSWORD_ERROR;
                    }
                } else {
                    $notice = "error";
                    $msg    = Msg::INVALID_LOGIN;
                }
            } else {
                $notice = "error";
                $msg    = Msg::PASSWORD_TOO_SHORT;
            }
        } else {
            $notice = "error";
            $msg    = Msg::PASSWORDS_MISMATCH;
        }

        $data = ["notice" => $notice, "message" => $msg];
        echo json_encode($data);
    }
}
