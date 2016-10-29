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

    public function validate(string $username, string $password, string $repeatpassword, string $email, int $apikey, string $vcode, string $reports) : array
    {
        $result = array("username"     => $this->validateUsername($username),
            "password"                 => $this->validatePassword($password, $repeatpassword),
            "email"                    => $this->validateEmail($email),
            "api"                      => $this->validateAPI($apikey, $vcode),
            "reports"                  => $this->validateReports($reports),
        );

        return $result;
    }

    private function validateUsername(string $username)
    {
        if (!$this->ValidateRequest->validateUsernameLength($username)) {
            return Msg::USERNAME_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateUsernameAvailability($username)) {
            return Msg::USER_ALREADY_EXISTS;
        }

    }

    private function validateEmail(string $email)
    {
        if (!$this->ValidateRequest->validateEmailFormat($email)) {
            return Msg::INVALID_EMAIL;
        }

        if (!$this->ValidateRequest->validateEmailAvailability($email)) {
            return Msg::EMAIL_ALREADY_TAKEN;
        }

    }

    private function validatePassword(string $password, string $repeatpassword)
    {
        if (!$this->ValidateRequest->validatePasswordLength($password)) {
            return Msg::PASSWORD_TOO_SHORT;
        }

        if (!$this->ValidateRequest->validateIdenticalPasswords($password, $repeatpassword)) {
            return Msg::PASSWORDS_MISMATCH;
        }
    }

    private function validateAPI(int $apikey, string $vcode)
    {
        return $this->ValidateRequest->validateAPI($apikey, $vcode);
    }

    private function validateReports($reports)
    {
        if (empty($reports)) {
            return Msg::INVALID_REPORT_SELECTION;
        }
    }

    public function getCharacters(int $apikey, string $vcode)
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

    public function verifyCharacters(array $chars, int $apikey, string $vcode)
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

    public function createAccount(string $username, string $password, string $email, int $apikey, string $vcode, string $reports, array $chars) : string
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
            $character_id = (int) $row;
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

    public function checkCharacterExists(int $character_id) : bool
    {
        $this->db->where('character_eve_idcharacter', $character_id);
        $check_user = $this->db->get('v_user_characters');

        if ($check_user->num_rows() >= 1) {
            return true;
        }
        return false;
    }
}
