<?php declare (strict_types = 1);
defined('BASEPATH') or exit('No direct script access allowed');

class Recovery extends CI_Controller
{
    private $email;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->load->model('common/Msg');
        $this->load->model('common/ValidateRequest');
        $this->email    = $_REQUEST['email'] ?? '';
        $this->username = $_REQUEST['user'] ?? '';
    }

    public function index(string $type = "password")
    {
        $data['view']      = "recovery/recover_" . $type . "_v";
        $data['no_header'] = 1;
        $this->load->view('main/_template_v', $data);
    }

    public function recoverPassword()
    {
        $this->load->model('Recovery_model');
        $user_data = $this->Recovery_model->getUserByEmail($this->username, $this->email);

        if (!$user_data) {
            sleep(3);
            $this->doRecoveryMsg("success");
        } else {
            $validation = $this->ValidateRequest->validateUserEmail($this->username, $this->email);
            if ($validation) {
                $this->startPasswordRecovery($user_data);
            } else {
                sleep(3);
                $this->doRecoveryMsg("success");
            }
        }
    }

    private function startPasswordRecovery(stdClass $user_data)
    {
        $this->load->model('common/Auth');
        $new_password = $this->Auth->generateRandomPassword();

        $this->load->model('Settings_model');
        $id_user = (int) $user_data->iduser;
        $change  = $this->Settings_model->changePassword($id_user, $new_password);

        if ($change) {
            $this->load->model('common/Email');
            $data['pw'] = $new_password;

            $email_body = $this->load->view('recovery/email/recover_password_v', $data, true);
            $to = $user_data->email;
            $from = FROM_EMAIL;
            $from_name = FROM_NAME;
            $subject = "Eve Trade Master - Password Recovery";

            $this->Email->send($to, $from, $from_name, $subject, $email_body);
            $this->doRecoveryMsg("success");
        } else {
            $this->doRecoveryMsg("error");
        }

    }

    private function doRecoveryMsg(string $type)
    {
        if ($type == "success") {
            $notice = "success";
            $msg    = Msg::PASSWORD_RECOVERY_SUCCESS;
        } else {
            $notice = "error";
            $msg    = Msg::PASSWORD_RECOVERY_ERROR;
        }
        
        echo json_encode(array("notice" => $notice, "message" => $msg)); 
    } 

}
