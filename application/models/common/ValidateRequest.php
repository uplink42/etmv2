<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;

class ValidateRequest extends CI_Model
{
    const MIN_PASSWORD_LENGTH = 6;
    const MIN_USERNAME_LENGTH = 6;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/Msg');
    }

    /**
     * Checks if a character belongs to an account
     * @param  int       $character_id 
     * @param  int       $user_id      
     * @param  bool|null $json         json result flag (for javascript requests)
     * @return [bool/json]              
     */
    public function checkCharacterBelong(int $character_id, int $user_id, bool $json = null): bool
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('iduser', $user_id);
        $query = $this->db->get('v_user_characters');

        if ($query->num_rows() != 0) {
            return true;
        } else if ($json) {
            echo Msg::INVALID_REQUEST;
        } else {
            return false;
        }
    }

    /**
     * Checks if a citadel tax entry belongs to a character
     * @param  int    $character_id 
     * @param  int    $tax_id       
     * @return bool               
     */
    public function checkCitadelOwnership(int $character_id, int $tax_id): bool
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $this->db->where('idcitadel_tax', $tax_id);
        $query = $this->db->get('citadel_tax');

        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if a stock list belongs to a user
     * @param  int    $list_id 
     * @param  int    $user_id 
     * @return bool          
     */
    public function checkStockListOwnership(int $list_id, int $user_id): bool
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
    public function checkTradeRouteOwnership(int $route_id, int $user_id): bool
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
    public function checkTransactionOwnership(string $transaction_id, int $user_id): bool
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
    public function validatePasswordLength(string $password): bool
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
    public function validateIdenticalPasswords(string $password, string $repeatpassword): bool
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
    public function validateUsernameLength(string $username): bool
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
    public function validateUsernameAvailability(string $username): bool
    {
        $this->db->where('username', $username);
        $existing_user = $this->db->get('user');

        if ($existing_user->num_rows() >= 1) {
            return false;
        }
        return true;
    }

    /**
     * Tests the email against a regex for validity
     * @param  string $email 
     * @return bool        
     */
    public function validateEmailFormat(string $email): bool
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
    public function validateEmailAvailability(string $email): bool
    {
        $this->db->where('email', $email);
        $existing_email = $this->db->get('user');
        if ($existing_email->num_rows() >= 1) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the api key is valid and has the right permissions
     * @param  int    $apikey 
     * @param  string $vcode  
     * @return void        
     */
    public function validateAPI(int $apikey, string $vcode)
    {
        try {
            $phealAPI = new Pheal($apikey, $vcode, "account");
            $response = $phealAPI->APIKeyInfo();

            $accessMask = $response->key->accessMask;
            $expiry     = $response->key->expires;

        } catch (\Pheal\Exceptions\PhealException $e) {
            log_message('error', 'validate api keys ' . $e->getMessage());
            //communication error, abort
            return Msg::INVALID_API_KEY;
        }

        if ($accessMask == "" && $response) {
            return Msg::INVALID_API_KEY;
        } else if ($accessMask != '82317323' && $accessMask != '1073741823' && $response) {
            return Msg::INVALID_API_MASK;
        } else if (!isset($expiry) && $response) {
            return Msg::INVALID_API_KEY;
        }


        //Using CURL to fetch API Access Mask
       /* $curl_url = "https://api.eveonline.com/account/APIKeyInfo.xml.aspx?keyID=" . $apikey . "&vCode=" . $vcode;

        $ch = curl_init($curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $apiInfo = new SimpleXMLElement($response);

            try {
                $this->checkXML($apiInfo->result->key);
                $accessMask = (int) $apiInfo->result->key->attributes()->accessMask;
            } catch (Exception $e) {
                return Msg::INVALID_API_KEY;
            }
        }
        curl_close($ch);

        if ($accessMask != '82317323') {
            return Msg::INVALID_API_MASK;
        }*/

        /*if (!$this->validateAPIAvailability($apikey)) {
            return Msg::API_ALREADY_EXISTS;
        }*/
    }

    /**
     * Checks if an api key is not in use by another character
     * @param  int    $apikey 
     * @return bool         
     */
    public function validateAPIAvailability(int $apikey): bool
    {
        $this->db->where('apikey', $apikey);
        $query = $this->db->get('v_user_characters');

        if ($query->num_rows() == 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if not empty response
     * depercated
     * @param  string $xml 
     * @return bool      
     */
    private function checkXML($xml)
    {
        if ($xml == "") {
            throw new Exception(Msg::INVALID_API_KEY);
        }
        return true;
    }

    /**
     * Get current CREST API status (online or offline)
     * @return bool
     */
    public function getCrestStatus(): bool
    {
        $url    = "https://crest-tq.eveonline.com/market/10000002/orders/sell/?type=https://crest-tq.eveonline.com/inventory/types/34/";
        $result = json_decode(file_get_contents($url), true);

        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * Get current XML API status
     * @return bool
     */
    public function testEndpoint(): bool
    {
        try {
            $pheal    = new Pheal();
            $response = $pheal->serverScope->ServerStatus();

            if (!is_numeric($response->onlinePlayers)) {
                return false;
            }
            return true;

        } catch (\Pheal\Exceptions\PhealException $e) {
            echo sprintf(
                "an exception was caught! Type: %s Message: %s",
                get_class($e),
                $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Checks if a username and email match any registered accounts
     * @param  string $username 
     * @param  string $email    
     * @return bool           
     */
    public function validateUserEmail(string $username, string $email): bool
    {
        $this->db->where('username', $username);
        $this->db->where('email', $email);
        $query = $this->db->get('user');

        if ($query->num_rows() != 0) {
            return true;
        }
        return false;
    }
}
