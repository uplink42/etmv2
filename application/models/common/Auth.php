<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Main authentication controller
 * Takes care of all login and password recovery operations
 */
class Auth extends CI_Model
{
    /**
     * Password encryption cost (higher cost takes longer to perform)
     */
    const COST = 10;

    /**
     * Indicates blowfish algorithm for password encryption
     */
    const BLOWFISH = "$2a$%02d$";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    /**
     * Generates a secure password according to our configuration
     * @param  [string] $password [initial password]
     * @return [array]            [encrypted password and salt]
     */
    public function createHashedPassword($password)
    {
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $salt = sprintf(self::BLOWFISH, self::COST) . $salt;
        // Hash the password with the salt
        $password_final = crypt($password, $salt);

        return array("password" => $password_final, "salt" => $salt);
    }

    /**
     * Randomly generates a new password for password recovery
     * @return [type] [description]
     */
    public function generateRandomPassword()
    {

    }

    /**
     * Validates user/password according to database records
     * Starts a session if successful
     * @param  [string] $username  [form submitted username]
     * @param  [string] $password  [form submitted password]
     * @param  [bool]   $nosession [wether to create a session or not]
     * @return [bool]              [validation result]
     */
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
