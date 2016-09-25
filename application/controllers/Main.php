<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'libraries/PHPMailer/PHPMailerAutoload.php');

class Main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    //loads the template view with the required content
    public function index($view = null)
    {
        $data['no_header'] = 1;
        $data['view']      = 'login/login_v';
        $this->load->view('main/_template_v', $data);
        //$this->load->view('main/header_v', $data);
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

    public function headerData($character_id, $aggr = 0)
    {
        $this->load->model('Nav_model');
        if ($this->Nav_model->checkCharacterBelong($character_id, $this->session->iduser)) {
            if($aggr == 0) {
                $result = json_encode($this->Nav_model->getHeaderData($character_id));
                echo $result;
            } else {
                $this->load->model('Login_model');
                $characters = $this->Login_model->getCharacterList($this->session->iduser);
                $chars = $characters['aggr'];
                $result = json_encode($this->Nav_model->getHeaderData($character_id, $chars));
                echo $result;
            }
            
        }
    }

    public function sendEmail()
    {
        $to = $_REQUEST['to'];
        $from = $_REQUEST['email'];
        $from_name = $_REQUEST['from_name'];
        $subject = $_REQUEST['subject'];
        $body = $_REQUEST['message'];

        global $error;
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug  = SMTPDEBUG; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = SMTPSECURE; // secure transfer enabled REQUIRED for GMail
        $mail->Host       = SMTPHOST;
        $mail->Port       = SMTPPORT;
        $mail->Username   = GUSER;
        $mail->Password   = GPWD;
        $mail->SetFrom($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AddAddress($to);
        $mail->IsHTML(true);
        //$mail->SMTPDebug = 2;
        if (!$mail->Send()) {
            //echo $error = 'Mail error: '.$mail->ErrorInfo; 
            $error = "Error sending email. Try again later.";
            $res = "error";
        }
        else {
            $error = 'Message sent to ' . $to . " " . "\n";
            $res = "success";
        }
        echo json_encode(array("notice" => $res, "message" => $error));
    }

}
