<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Pheal\Pheal;
//load namespaced library

class Register_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($username, $password, $repeatpassword, $email, $apikey, $vcode, $reports)
    {
        $result = array("username" => $this->validateUsername($username),
            "password"                 => $this->validatePassword($password, $repeatpassword),
            "email"                    => $this->validateEmail($email),
            "api"                      => $this->validateAPI($apikey, $vcode),
            "reports"                  => $this->validateReports($reports),
        );

        return $result;
    }

    private function validateUsername($username)
    {
        if (strlen($username) < 6) {
            return "Username is too short (minimum 6 characters)";
        }

        $this->db->where('username', $username);
        $existing_user = $this->db->get('user');
        if ($existing_user->num_rows() >= 1) {
            return "Username is already taken";
        }
    }

    private function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return "Invalid e-mail format";
        }

        $this->db->where('email', $email);
        $existing_email = $this->db->get('user');
        if ($existing_email->num_rows() >= 1) {
            return "E-mail is already taken";
        }
    }

    private function validatePassword($password, $repeatpassword)
    {
        if (strlen($password) < 6) {
            return "Password too short (minimum 6 characters)";
        } else if ($password != $repeatpassword) {
            return "Passwords don't match";
        }
    }

    private function validateAPI($apikey, $vcode)
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
                return "Invalid API Key or vCode. Make sure you use the generation link";
            }
        }
        curl_close($ch);

        if ($accessMask != '82317323') {
            return "Invalid permissions for the API Key you supplied. Make sure you use the generation link";
        }
    }

    private function checkXML($xml)
    {
        if ($xml == "") {
            throw new Exception("Invalid API Key or vCode. Make sure you use the generation link");
        }
        return true;
    }

    private function validateReports($reports)
    {
        if (empty($reports)) {
            return "Invalid report selection";
        }
    }

    public function getCharacters($apikey, $vcode)
    {
        $pheal  = new Pheal($apikey, $vcode);
        $result = $pheal->accountScope->APIKeyInfo();

        $characters = array();
        foreach ($result->key->characters as $character) {
            array_push($characters, array(array("name" => $character->characterName), array("id" => $character->characterID)));
        }

        return $characters;
    }

    //check if a character belongs to the API supplied
    public function verifyCharacters($chars, $apikey, $vcode)
    {
        $pheal  = new Pheal($apikey, $vcode);
        $result = $pheal->accountScope->APIKeyInfo();

        $chars_api  = array();
        $chars_name = array();
        $empty      = array();

        foreach ($result->key->characters as $character) {
            array_push($chars_api, $character->characterID);
            array_push($chars_name, $character->characterName);
        }

        if (array_intersect(array_diff($chars, $chars_api), $chars_api) != $empty) {
            return false; //character does not belong
        }
        return true;
    }

    public function createAccount($username, $password, $email, $apikey, $vcode, $reports, $chars)
    {
        $error = "";

        $dt = new DateTime();
        $tz = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);
        $datetime = $dt->format('Y-m-d H:i:s');

        $cost = 10;
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;
        // Hash the password with the salt
        $password_final = crypt($password, $salt);

        $this->db->trans_start();

        //query1
        $data1 = array(
            "iduser"            => null,
            "username"          => $username,
            "registration_date" => $datetime,
            "password"          => $password_final,
            "reports"           => $reports,
            "email"             => $email,
            "salt"              => $salt,
            "login_count"       => 0,
            "updating"          => 0,
        );
        $this->db->insert('user', $data1);
        $user_id = $this->db->insert_id();

        //query2
        $data2 = array(
            "apikey" => $apikey,
            "vcode"  => $vcode,
        );
        $this->db->query("INSERT IGNORE INTO api(apikey, vcode) VALUES ('$apikey', '$vcode')");

        //print_r($chars);
        foreach ($chars as $row) {
            $character_id = $row;
            //check if character already exists in db
            if ($this->checkCharacterExists($character_id)) {
                $this->db->trans_rollback();
                $error = "One or more of the characters you selected already belongs to another account.";
                return $error;
            }

            $pheal          = new Pheal($apikey, $vcode, "char"); //fetch character name
            $result         = $pheal->CharacterSheet(array("characterID" => $character_id));
            $character_name = $this->security->xss_clean($result->name);

            //query3
            $eve_idcharacter  = $character_id;
            $name             = $this->db->escape($character_name);
            $balance          = 0;
            $api_apikey       = $apikey;
            $networth         = 0;
            $escrow           = 0;
            $total_sell       = 0;
            $broker_relations = 0;
            $accounting       = 0;

            //$this->db->replace('characters', $data3);
            $this->db->query("INSERT INTO characters
                (eve_idcharacter, name, balance, api_apikey, networth, escrow, total_sell, broker_relations, accounting)
                  VALUES ('$eve_idcharacter', " . $name . ", '$balance', '$api_apikey', '$networth', '$escrow', '$total_sell', '$broker_relations', '$accounting')
                      ON DUPLICATE KEY UPDATE eve_idcharacter = '$eve_idcharacter', name=" . $name . ", api_apikey = '$api_apikey', networth='$networth',
                          escrow='$escrow', total_sell='$total_sell', broker_relations='$broker_relations', accounting='$accounting'");
            log_message('error', $this->db->last_query());

            //query4
            $data4 = array(
                "idaggr"                    => null,
                "user_iduser"               => $user_id,
                "character_eve_idcharacter" => $character_id,
            );
            $this->db->insert('aggr', $data4);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return "Error contacting the database. Please try again.";
        } else {
            return "ok";
        }
    }

    //check if a character already BELONGS to another account
    private function checkCharacterExists($character_id)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $check_user = $this->db->get('v_user_characters');

        if ($check_user->num_rows() >= 1) {
            log_message('error', 'wtf');
            return true;
        }
        return false;
    }
}
