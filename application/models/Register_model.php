<?php
if (!defined('BASEPATH')) {
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
        $this->load->model('common/ValidateRequest');
    }

    /**
     * Start all validations wether we can register a user account
     * @param  string $username
     * @param  string $password
     * @param  string $repeatpassword
     * @param  string $email
     * @param  int    $apikey
     * @param  string $vcode
     * @param  string $reports
     * @return array
     */
    public function validate(string $username, string $password, string $repeatpassword, string $email, int $apikey, string $vcode, string $reports): array
    {
        $result = array("username"     => $this->validateUsername($username),
            "password"                 => $this->validatePassword($password, $repeatpassword),
            "email"                    => $this->validateEmail($email),
            "api"                      => $this->validateAPI($apikey, $vcode),
            "reports"                  => $this->validateReports($reports),
        );

        return $result;
    }

    /**
     * Apply all username related validations
     * @param  string $username
     * @return [type]
     */
    private function validateUsername(string $username)
    {
        if (!$this->ValidateRequest->validateUsernameLength($username)) {
            return Msg::USERNAME_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateUsernameAvailability($username)) {
            return Msg::USER_ALREADY_EXISTS;
        }
    }

    /**
     * Apply all email related validations
     * @param  string $email
     * @return [type]
     */
    private function validateEmail(string $email)
    {
        if (!$this->ValidateRequest->validateEmailFormat($email)) {
            return Msg::INVALID_EMAIL;
        }

        if (!$this->ValidateRequest->validateEmailAvailability($email)) {
            return Msg::EMAIL_ALREADY_TAKEN;
        }
    }

    /**
     * Apply all password related validations
     * @param  string $password
     * @param  string $repeatpassword
     * @return [type]
     */
    private function validatePassword(string $password, string $repeatpassword)
    {
        if (!$this->ValidateRequest->validatePasswordLength($password)) {
            return Msg::PASSWORD_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateIdenticalPasswords($password, $repeatpassword)) {
            return Msg::PASSWORDS_MISMATCH;
        }
    }

    /**
     * Apply all API key related validations
     * @param  int    $apikey
     * @param  string $vcode
     * @return [type]
     */
    private function validateAPI(int $apikey, string $vcode)
    {
        return $this->ValidateRequest->validateAPI($apikey, $vcode);
    }

    /**
     * Apply all user report related validations
     * @param  [type] $reports
     * @return [type]
     */
    private function validateReports($reports)
    {
        if (empty($reports)) {
            return Msg::INVALID_REPORT_SELECTION;
        }
    }

    /**
     * Get a list of all API Key characters
     * @param  int    $apikey
     * @param  string $vcode
     * @return array
     */
    public function getCharacters(int $apikey, string $vcode): array
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
     * Verify if characters belong to this api key
     * @param  array  $chars
     * @param  int    $apikey
     * @param  string $vcode
     * @return bool
     */
    public function verifyCharacters(array $chars, int $apikey, string $vcode): bool
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
     * After all validations succeed, create an account
     * @param  array  $data
     * @return string
     */
    public function createAccount(array $data): array
    {
        $error = "";
        $dt    = new DateTime();
        $tz    = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);
        $datetime = $dt->format('Y-m-d H:i:s');

        $this->load->model('common/Auth');
        $hashed = $this->Auth->createHashedPassword($data['password']);

        $this->db->trans_start();
        $data_user = array(
            "username"                => $data['username'],
            "registration_date"       => $datetime,
            "password"                => $hashed['password'],
            "reports"                 => $data['reports'],
            "email"                   => $data['email'],
            "salt"                    => $hashed['salt'],
            "default_buy_behaviour"   => $data['default_buy'],
            "default_sell_behaviour"  => $data['default_sell'],
            "cross_character_profits" => $data['x_character'],
            "ignore_citadel_tax"      => $data['null_citadel_tax'],
            "ignore_station_tax"      => $data['null_station_tax'],
            "ignore_outpost_tax"      => $data['null_outpost_tax'],
            "ignore_buy_tax"          => $data['null_buy_tax'],
            "ignore_sell_tax"         => $data['null_sell_tax'],
            "login_count"             => 0,
            "updating"                => 0,
        );
        $this->db->insert('user', $data_user);
        $user_id = $this->db->insert_id();

        $key = $data['apikey'];
        $vcode = $data['vcode'];
        $this->db->query("INSERT IGNORE INTO api(apikey, vcode) VALUES ('$key', '$vcode')");

        foreach ($data['chars'] as $row) {
            $character_id = (int) $row;
            //check if character already exists in db
            if ($this->checkCharacterExists($character_id)) {
                $this->db->trans_rollback();
                $error = Msg::CHARACTER_ALREADY_TAKEN;
                return $error;
            }

            $pheal            = new Pheal($data['apikey'], $data['vcode'], "char"); //fetch character name
            $result           = $pheal->CharacterSheet(array("characterID" => $character_id));
            $character_name   = $this->security->xss_clean($result->name);
            $eve_idcharacter  = $character_id;
            $name             = $this->db->escape($character_name);
            $balance          = 0;
            $api_apikey       = $data['apikey'];
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

            $data_assoc = array(
                "idaggr"                    => null,
                "user_iduser"               => $user_id,
                "character_eve_idcharacter" => $character_id,
            );

            $this->db->insert('aggr', $data_assoc);
        }

        $this->db->trans_complete();

        $result = [];
        if ($this->db->trans_status() === false) {
            $result['success'] = false;
            $result['msg']     = Msg::DB_ERROR;
        } else {
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Check if a character is already associated with another user
     * @param  int    $character_id
     * @return bool
     */
    public function checkCharacterExists(int $character_id): bool
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $check_user = $this->db->get('v_user_characters');

        if ($check_user->num_rows() >= 1) {
            return true;
        }
        return false;
    }
}
