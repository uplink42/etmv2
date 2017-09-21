<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

final class Auth
{
    const COST     = 10;
    const BLOWFISH = "$2a$%02d$";

    /**
     * Creates a strong encrypted password
     * @param  string $password
     * @return array
     */
    public static function createHashedPassword(string $password): array
    {
        $salt           = strtr(base64_encode(random_bytes(16)), '+', '.');
        $salt           = sprintf(self::BLOWFISH, self::COST) . $salt;
        $password_final = crypt($password, $salt);

        return array("password" => $password_final, "salt" => $salt);
    }

    /**
     * Generates a random string for a new password
     * @return string
     */
    public static function generateRandomPassword()
    {
        return $this->getRandomString("abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_!#$%&=?", 24);
    }

    /**
     * Generates a random string for a given number of characters
     * @param  string $valid_chars  possible string characters
     * @param  int $length          string length
     * @return string
     */
    private static function getRandomString(string $valid_chars, int $length): string
    {
        $random_string   = "";
        $num_valid_chars = strlen($valid_chars);

        for ($i = 0; $i < $length; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);
            $random_char = $valid_chars[$random_pick - 1];
            $random_string .= $random_char;
        }

        return $random_string;
    }

    public static function validateSession(array $session)
    {
        if (!isset($session['username']) || !isset($session['email']) || !isset($session['password'])) {
            return false;
        }

        $this->db->where('username', $session['username']);
        $this->db->where('email', $session['email']);
        $this->db->where('password', $session['password']);
        $sql    = $this->db->get('user');
        $result = $sql->num_rows();

        if ($result > 0) {
            return true;
        }

        return false;
    }
}
