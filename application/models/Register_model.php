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
        $this->load->model('common/Msg');
        $this->load->model('ValidateRequest');
    }

    /**
     * Begins the validation routine for account creation
     * Returns an array with the result of each validation step
     * @param  [string] $username       [user submited username]
     * @param  [string] $password       [user submited password]
     * @param  [string] $repeatpassword [user submited repeated password]
     * @param  [string] $email          [user submited email]
     * @param  [string] $apikey         [user submited apikey]
     * @param  [string] $vcode          [user submited vcode]
     * @param  [string] $reports        [user submited reports]
     * @return [array]                  [validation result]
     */
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

    /**
     * Username validation
     * Returns false if successful, or an error message otherwise
     * @param  [string] $username [user submited username]
     * @return [string]           [error message]
     */
    private function validateUsername($username)
    {
        if (!$this->ValidateRequest->validateUsernameLength($username)) {
            return Msg::USERNAME_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateUsernameAvailability($username)) {
            return Msg::USER_ALREADY_EXISTS;
        }

    }

    /**
     * Email validation
     * Returns false if successful, or an error message otherwise
     * @param  [string] $email [user submitted email]
     * @return [string]        [error message]
     */
    private function validateEmail($email)
    {
        if (!$this->ValidateRequest->validateEmailFormat($email)) {
            return Msg::INVALID_EMAIL;
        }

        if (!$this->ValidateRequest->validateEmailAvailability($email)) {
            return Msg::EMAIL_ALREADY_TAKEN;
        }

    }

    /**
     * Password validation
     * Returns false if successful, or an error message otherwise
     * @param  [string] $password       [user submitted password]
     * @param  [string] $repeatpassword [user submitted repeated password]
     * @return [string]                 [error message]
     */
    private function validatePassword($password, $repeatpassword)
    {
        if (!$this->ValidateRequest->validatePasswordLength($password)) {
            return Msg::PASSWORD_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateIdenticalPasswords($password, $repeatpassword)) {
            return Msg::PASSWORDS_MISMATCH;
        }
    }

    /**
     * APIkey validation
     * Returns false if successful, or an error message otherwise
     * @param  [string] $apikey [user submitted apikey]
     * @param  [string] $vcode  [user submitted vcode]
     * @return [string]         [error message]
     */
    private function validateAPI($apikey, $vcode)
    {
        return $this->ValidateRequest->validateAPI($apikey, $vcode);
    }

    /**
     * Report selection validation
     * Returns false if successful, or an error message otherwise
     * @param  [string] $reports [user submitted report selection]
     * @return [string]          [error message]
     */
    private function validateReports($reports)
    {
        if (empty($reports)) {
            return Msg::INVALID_REPORT_SELECTION;
        }
    }

    /**
     * Returns a list of all characters within a valid apikey
     * @param  [string] $apikey [user submitted apikey]
     * @param  [string] $vcode  [user submitted vcode]
     * @return [array]          [character list]
     */
    public function getCharacters($apikey, $vcode)
    {
        $pheal  = new Pheal($apikey, $vcode);
        $result = $pheal->accountScope->APIKeyInfo();

        $characters = array();
        foreach ($result->key->characters as $character) {
            array_push($characters, array(
                array("name" => $character->characterName), array("id" => $character->characterID),
                )
            );
        }

        return $characters;
    }

    /**
     * Checks if a list of characters belongs to the supplied API key
     * @param  [array]  $chars  [user submitted characters]
     * @param  [string] $apikey [user submitted apikey]
     * @param  [string] $vcode  [user submitted vcode]
     * @return [bool]           [validation result]
     */
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

        //calculate differences between api result and selected characters
        //and intersect the result
        if (array_intersect(array_diff($chars, $chars_api), $chars_api) != $empty) {
            return false;
        }
        return true;
    }

    /**
     * Creates an account after all validations are completed
     * Returns an error message if something goes wrong
     * @param  [string] $username [user submitted username]
     * @param  [string] $password [user submitted password]
     * @param  [string] $email    [user submitted email]
     * @param  [string] $apikey   [user submitted apikey]
     * @param  [string] $vcode    [user submitted vcode]
     * @param  [string] $reports  [user submitted reports]
     * @param  [array]  $chars    [user submitted character list]
     * @return [string]           [error message]
     */
    public function createAccount($username, $password, $email, $apikey, $vcode, $reports, $chars)
    {
        $error = "";

        $dt = new DateTime();
        $tz = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);
        $datetime = $dt->format('Y-m-d H:i:s');

        $this->load->model('common/Auth');
        $hashed = $this->Auth->createHashedPassword($password);

        $this->db->trans_start();

        $data1 = array(
            "username"          => $username,
            "registration_date" => $datetime,
            "password"          => $hashed['password'],
            "reports"           => $reports,
            "email"             => $email,
            "salt"              => $hashed['salt'],
            "login_count"       => 0,
            "updating"          => 0,
        );
        $this->db->insert('user', $data1);
        $user_id = $this->db->insert_id();

        $data2 = array(
            "apikey" => $apikey,
            "vcode"  => $vcode,
        );
        $this->db->query("INSERT IGNORE INTO api(apikey, vcode) VALUES ('$apikey', '$vcode')");

        foreach ($chars as $row) {
            $character_id = $row;
            //check if character already exists in db
            if ($this->checkCharacterExists($character_id)) {
                $this->db->trans_rollback();
                $error = Msg::CHARACTER_ALREADY_TAKEN;
                return $error;
            }

            $pheal          = new Pheal($apikey, $vcode, "char"); //fetch character name
            $result         = $pheal->CharacterSheet(array("characterID" => $character_id));
            $character_name = $this->security->xss_clean($result->name);

            $eve_idcharacter  = $character_id;
            $name             = $this->db->escape($character_name);
            $balance          = 0;
            $api_apikey       = $apikey;
            $networth         = 0;
            $escrow           = 0;
            $total_sell       = 0;
            $broker_relations = 0;
            $accounting       = 0;

            $this->db->query("INSERT INTO characters
                (eve_idcharacter, name, balance, api_apikey, networth, escrow, total_sell, broker_relations, accounting)
                  VALUES ('$eve_idcharacter', " . $name . ", '$balance', '$api_apikey', '$networth', '$escrow', '$total_sell', '$broker_relations', '$accounting')
                      ON DUPLICATE KEY UPDATE eve_idcharacter = '$eve_idcharacter', name=" . $name . ", api_apikey = '$api_apikey', networth='$networth',
                          escrow='$escrow', total_sell='$total_sell', broker_relations='$broker_relations', accounting='$accounting'");

            $data4 = array(
                "idaggr"                    => null,
                "user_iduser"               => $user_id,
                "character_eve_idcharacter" => $character_id,
            );

            $this->db->insert('aggr', $data4);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return Msg::DB_ERROR;
        } else {
            return "ok";
        }
    }

    /**
     * Checks if a character already belongs to another user account
     * We don't allow this to happen
     * @param  [int]  $character_id [eve character id]
     * @return [bool]               [result]
     */
    private function checkCharacterExists($character_id)
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $check_user = $this->db->get('v_user_characters');

        if ($check_user->num_rows() >= 1) {
            return true;
        }
        return false;
    }
}
