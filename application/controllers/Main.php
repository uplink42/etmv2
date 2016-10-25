<?php declare (strict_types = 1);
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/PHPMailer/PHPMailerAutoload.php';

class Main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    public function index(string $view = null)
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->load->view('main/_template_v', $data);
    }

    public function login()
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->load->view('main/_template_v', $data);
    }

    public function register()
    {
        $data['no_header'] = 1;
        $data['view']      = 'register/register_v';
        $this->load->view('main/_template_v', $data);
    }

    public function headerData(int $character_id, bool $aggr = null)
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

    public function sendEmail()
    {
        $to        = $_REQUEST['to'];
        $from      = $_REQUEST['email'];
        $from_name = $_REQUEST['from_name'];
        $subject   = $_REQUEST['subject'];
        $body      = $_REQUEST['message'];

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

    public function getMsgHandles()
    {
        $refl = new ReflectionClass('Msg');
        $data = $refl->getConstants();
        echo json_encode($data);
    }

}
