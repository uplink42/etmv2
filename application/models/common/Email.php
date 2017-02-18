<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'libraries/PHPMailer/PHPMailerAutoload.php');

class Email extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    /**
     * Dispatch an email with the given parameters using phpmailer
     * @param  string $to        destination
     * @param  string $from      sender
     * @param  string $from_name sender name
     * @param  string $subject   email subject
     * @param  string $body      email body
     * @return bool            send result
     */
    public function send(string $to, string $from, string $from_name, string $subject, string $body) : bool
    {
        global $error;
        $mail = new PHPMailer();
        $mail->IsSMTP();
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
            return false;
        }
        return true;
    }
}
