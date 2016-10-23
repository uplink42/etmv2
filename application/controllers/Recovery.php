<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class Recovery extends CI_Controller
{
    private $email;

    public function __construct()
    {
        parent::__construct();
        $this->db->cache_off();
        $this->email = $_REQUEST['email'] ?? '';
    }

    public function index()
    {
        $data['view'] = "recovery/recover_user_password_v";
        $data['no_header'] = 1;
        $this->load->view('main/_template_v', $data);

    }

    public function recoverPassword()
    {
        $this->load->model('Recovery_model');
        $user_data = $this->Recovery_model->getUserByEmail($this->email);

        if(!$user_data) {
        } else {
            $this->startRecovery($user_data);
        }
    }

    private function startRecovery(stdClass $user_data)
    {
        $this->load->model('common/Auth');
        $new_password = $this->Auth->generateRandomPassword();

        $this->load->model('Settings_model');
        $id_user = (int) $user_data->iduser;
        $change  = $this->Settings_model->changePassword($id_user, $new_password);

        if ($change) {
            $notice = "success";
            $msg    = Msg::PASSWORD_RECOVERY_SUCCESS;
        }

    }

}
