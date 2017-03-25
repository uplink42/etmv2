<?php
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

    /**
     * Creates a strong encrypted password
     * @param  string $password 
     * @return array           
     */
    public function createHashedPassword(string $password) : array
    {
        $salt = strtr(base64_encode(random_bytes(16)), '+', '.');
        $salt = sprintf(self::BLOWFISH, self::COST) . $salt;
        $password_final = crypt($password, $salt);

        return array("password" => $password_final, "salt" => $salt);
    }

    /**
     * Generates a random string for a new password
     * @return string
     */
    public function generateRandomPassword()
    {
        return $this->getRandomString("abcdefghijklmnopqrstuwxyz1234567890_!#$%&=?", 10);
    }

    /**
     * Checks if username/password match
     * @param  string       $username  
     * @param  string       $password  
     * @param  bool|boolean $nosession if we should create a session
     * @return bool                 
     */
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
                $this->etmsession->setData($data);
            }
            return true;
        }

        return false;
    }

    /**
     * Generates a random string for a given number of characters
     * @param  string $valid_chars  possible string characters
     * @param  int $length          string length
     * @return string
     */
    private function getRandomString(string $valid_chars, int $length) : string
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
