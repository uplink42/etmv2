<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/PHPMailer/PHPMailerAutoload.php';

final class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    /**
     * Loads the main template
     * @param  string|null $view
     * @return void
     */
    public function index($view = null): void
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->twig->display('main/_template_v', $data);
    }

    /**
     * Loads the login page
     * @return void
     */
    public function login(): void
    {
        // check if session is already present
        $this->load->library('etmsession');
        $this->load->model('common/Auth', 'auth');
        if (isset($_SESSION['username']) && isset($_SESSION['email']) && $this->auth->validateSession($_SESSION)) {
            $data['auth'] = true;
        } else {
            $data['auth'] = false;
        }

        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->twig->display('main/_template_v', $data);
    }

    /**
     * Loads the registration page
     * @return void
     */
    public function register(): void
    {
        $data['no_header'] = 1;
        $data['view']      = 'register/register_v';
        $this->twig->display('main/_template_v', $data);
    }

    /**
     * Loads the header account data
     * @param  int       $character_id
     * @param  bool|null $aggr
     * @return string json
     */
    public function headerData(int $character_id, bool $aggr = null): void
    {
        $this->load->model('Nav_model');
        if ($this->ValidateRequest->checkCharacterBelong($character_id, $this->user_id, true)) {
            if (!$aggr) {
                $result = json_encode($this->Nav_model->getHeaderData($character_id));
                echo $result;
            } else {
                $this->load->model('Login_model');
                $characters = $this->Login_model->getCharacterList($this->user_id);
                $chars      = $characters['aggr'];
                $result     = json_encode($this->Nav_model->getHeaderData($character_id, $chars));
                echo $result;
            }
        }
    }

    /**
     * Dispatches an email
     * @return string json
     */
    public function sendEmail(): void
    {
        $to        = $_REQUEST['to'];
        $from      = $_REQUEST['email'];
        $from_name = $_REQUEST['from_name'];
        $subject   = $_REQUEST['subject'];
        $body      = $_REQUEST['email'] . ' -> ' . $_REQUEST['message'];

        $this->load->model('common/Email');
        $mail = $this->Email->send($to, $from, $from_name, $subject, $body);
        //$mail->SMTPDebug = 2;
        if (!$mail) {
            $error = Msg::EMAIL_SEND_FAILURE;
            $res   = "error";
        } else {
            $error = Msg::EMAIL_SEND_SUCCESS . $to . " " . "\n";
            $res   = "success";
        }
        echo json_encode(array("notice" => $res, "message" => $error));
    }

    /**
     * Loads the error messages to the client
     * @return string json
     */
    public function getMsgHandles(): void
    {
        $refl = new ReflectionClass('Msg');
        $data = $refl->getConstants();
        echo json_encode($data);
    }
}
