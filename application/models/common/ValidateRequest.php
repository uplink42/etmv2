<?php declare (strict_types = 1);
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

    public function validatePasswordLength(string $password): bool
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return false;
        }

        return true;
    }

    public function validateIdenticalPasswords(string $password, string $repeatpassword): bool
    {
        if ($password != $repeatpassword) {
            return false;
        }

        return true;
    }

    public function validateUsernameLength(string $username): bool
    {
        if (strlen($username) < self::MIN_USERNAME_LENGTH) {
            return false;
        }

        return true;
    }

    public function validateUsernameAvailability(string $username): bool
    {
        $this->db->where('username', $username);
        $existing_user = $this->db->get('user');

        if ($existing_user->num_rows() >= 1) {
            return false;
        }

        return true;
    }

    public function validateEmailFormat(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    public function validateEmailAvailability(string $email): bool
    {
        $this->db->where('email', $email);
        $existing_email = $this->db->get('user');
        if ($existing_email->num_rows() >= 1) {
            return false;
        }

        return true;
    }

    public function validateAPI(int $apikey, string $vcode): string
    {
        //Using CURL to fetch API Access Mask
        $curl_url = "https://api.eveonline.com/account/APIKeyInfo.xml.aspx?keyID=" . $apikey . "&vCode=" . $vcode;

        $ch = curl_init($curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);

        // If curl_exec() fails/throws an error, the function will return false
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
        }

        if (!$this->validateAPIAvailability($apikey)) {
            return Msg::API_ALREADY_EXISTS;
        }
    }

    public function validateAPIAvailability(int $apikey): bool
    {
        $this->db->where('apikey', $apikey);
        $query = $this->db->get('api');

        if ($query->num_rows() == 0) {
            return true;
        }

        return false;

    }

    private function checkXML(string $xml): bool
    {
        if ($xml == "") {
            throw new Exception(Msg::INVALID_API_KEY);
        }
        return true;
    }

    public function getCrestStatus(): bool
    {
        $url    = "https://crest-tq.eveonline.com/market/10000002/orders/sell/?type=https://crest-tq.eveonline.com/inventory/types/34/";
        $result = json_decode(file_get_contents($url), true);

        if ($result) {
            return true;
        }

        return false;
    }

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

}
