<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Model
{
    const COST = 10;

    const BLOWFISH = "$2a$%02d$";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    public function createHashedPassword(string $password) : array
    {
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $salt = sprintf(self::BLOWFISH, self::COST) . $salt;
        // Hash the password with the salt
        $password_final = crypt($password, $salt);

        return array("password" => $password_final, "salt" => $salt);
    }

    public function generateRandomPassword()
    {
        return $this->getRandomString("abcdefghijklmnopqrstuwxyz1234567890_!#$%&=", 7);
    }

    public function validateLogin(string $username, string $password, bool $nosession = false) : bool
    {
        $this->db->where('username', $username);
        $query_salt                             = $this->db->get('user');
        isset($query_salt->row()->salt) ? $salt = $query_salt->row()->salt : $salt = "";

        $password_salt = crypt($password, $salt);

        $this->db->where('username', $username);
        $this->db->where('password', $password_salt);
        $query_pw = $this->db->get('user');

        if ($query_pw->num_rows() == 1) {
            $row  = $query_pw->row();
            $data = array(
                'username' => $row->username,
                'password' => $row->password,
                'email'    => $row->email,
            );
            if(!$nosession) {
                $this->session->set_userdata($data);
            }
            return true;
        }

        return false;
    }

    private function getRandomString($valid_chars, $length)
    {
        $random_string = "";
        $num_valid_chars = strlen($valid_chars);
        
        for ($i = 0; $i < $length; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick-1];
            $random_string .= $random_char;
        }
        
        return $random_string;
    }
}
