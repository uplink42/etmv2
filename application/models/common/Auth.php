<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth extends CI_Model
{
    const COST = 10;
    const BLOWFISH = $2a$%02d$;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    public function createHashedPassword()
    {
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf(self::BLOWFISH, $self::COST) . $salt;
        // Hash the password with the salt
        $password_final = crypt($password, $salt);

        return $password_final;
    }

    public function generateRandomPassword()
    {

    }

    public function validateLogin($username, $password, $nosession = null)
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

}
