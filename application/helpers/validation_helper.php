<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;

class ValidateRequest
{
    const MIN_PASSWORD_LENGTH = 6;
    const MIN_USERNAME_LENGTH = 6;

    /**
     * Checks if a character belongs to an account
     * @param  int       $character_id 
     * @param  int       $user_id      
     * @param  bool|null $json         json result flag (for javascript requests)
     * @return [bool/json]              
     */
    public static function checkCharacterBelong($idCharacter, int $idUser, bool $json = null): bool
    {
        $ci =&get_instance();
        $ci->load->model('Aggr_model', 'aggr');
        $characterMatch = $ci->aggr->getOne(array('character_eve_idcharacter' => $idCharacter, 'user_iduser' => $idUser));

        return $characterMatch ? true : false;
    }

    /**
     * Checks if a citadel tax entry belongs to a character
     * @param  int    $character_id 
     * @param  int    $tax_id       
     * @return bool               
     */
    public static function checkCitadelOwnership(int $idCharacter, int $idTax): bool
    {
        $ci =&get_instance();
        $ci->load->model('Citadel_tax_model', 'citadel_tax');
        $taxMatch = $ci->citadel_tax->getOne(array('character_eve_idcharacter' => $idCharacter, 'idcitadel_tax' => $idTax));

        return $taxMatch ? true : false;
    }

    /**
     * Checks if a stock list belongs to a user
     * @param  int    $list_id 
     * @param  int    $user_id 
     * @return bool          
     */
    public static function checkStockListOwnership(int $list_id, int $user_id): bool
    {
        $this->db->select('itemlist.iditemlist');
        $this->db->from('itemlist');
        $this->db->join('user', 'user.iduser = itemlist.user_iduser');
        $this->db->where('user.iduser', $user_id);
        $this->db->where('itemlist.iditemlist', $list_id);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a traderoute belongs to a user
     * @param  int    $route_id 
     * @param  int    $user_id  
     * @return bool           
     */
    public static function checkTradeRouteOwnership(int $route_id, int $user_id): bool
    {
        $this->db->where('user_iduser', $user_id);
        $this->db->where('idtraderoute', $route_id);
        $query = $this->db->get('traderoutes');
        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a transaction belongs to a user
     * @param  string $transaction_id 
     * @param  int    $user_id        
     * @return bool                 
     */
    public static function checkTransactionOwnership(string $transaction_id, int $user_id): bool
    {
        $this->load->model('Login_model');
        $result = $this->Login_model->getCharacterList($user_id);
        $chars  = $result['aggr'];
        if (strlen($chars) == 0) {
            return false;
        } else {
            $this->db->select('character_eve_idcharacter, idbuy');
            $this->db->where('character_eve_idcharacter IN ' . $chars);
            $this->db->where('idbuy', $transaction_id);
            $query = $this->db->get('transaction');
            if ($query->num_rows() != 0) {
                return true;
            }

            return false;
        }
    }

    /**
     * Checks wether the password meets the current min length
     * @param  string $password 
     * @return bool           
     */
    public static function validatePasswordLength(string $password): bool
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return false;
        }

        return true;
    }

    /**
     * Checks if both passwords are identical
     * @param  string $password       
     * @param  string $repeatpassword 
     * @return bool                 
     */
    public static function validateIdenticalPasswords(string $password, string $repeatpassword): bool
    {
        if ($password != $repeatpassword) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the username meets the min length
     * @param  string $username 
     * @return bool           
     */
    public static function validateUsernameLength(string $username): bool
    {
        if (strlen($username) < self::MIN_USERNAME_LENGTH) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the username is available and not taken
     * @param  string $username 
     * @return bool           
     */
    public static function validateUsernameAvailability(string $username): bool
    {
        $ci =& get_instance();
        $ci->load->model('User_model', 'user');
        $userCount = $ci->user->countAll(['username' => $username]);

        return $userCount >= 1 ? false : true;
    }

    /**
     * Tests the email against a regex for validity
     * @param  string $email 
     * @return bool        
     */
    public static function validateEmailFormat(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    /**
     * Checks if an email is not taken
     * @param  string $email 
     * @return bool        
     */
    public static function validateEmailAvailability(string $email): bool
    {
        $ci =& get_instance();
        $ci->load->model('User_model', 'user');
        $emailCount = $ci->user->countAll(['email' => $email]);
        
        return $emailCount >= 1 ? false : true;
    }

    /**
     * Checks if a username and email match any registered accounts
     * @param  string $username 
     * @param  string $email    
     * @return bool           
     */
    public static function validateUserEmail(string $username, string $email): bool
    {
        $ci =&get_instance();
        $ci->load->model('User_model', 'user');
        $emailMatch = $ci->user->getOne(array('username' => $username, 'email' => $email));

        $this->db->where('username', $username);
        $this->db->where('email', $email);
        $query = $this->db->get('user');
        if ($query->num_rows() != 0) {
            return true;
        }

        return false;
    }

    public static function validateCharacterAvailability(string $idCharacter)
    {
        $ci =&get_instance();
        $ci->load->model('Characters_model', 'character');
        $characterMatch = $ci->character->getOne(array('eve_idcharacter' => $idCharacter));
        if ($characterMatch) {
            return false;
        }

        return true;
    }
}
