<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Pheal\Pheal;

final class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('msg');
        $this->load->helper('validation');
        $this->load->library('twig');
    }

    public function index()
    {
        $data['no_header'] = 1;
        $data['view']      = 'register/register_v';
        $this->twig->display('main/_template_v', $data);
    }

    public function processData(): void
    {
        $username       = $this->input->post('username', true);
        $password       = $this->input->post('password', true);
        $repeatpassword = $this->input->post('repeatpassword', true);
        $email          = $this->input->post('email', true);
        $apikey         = (int) $this->input->post('apikey', true);
        $vcode          = $this->input->post('vcode', true);
        $reports        = $this->input->post('reports');

        $result = array("username" => $this->validateUsername($username),
                        "password" => $this->validatePassword($password, $repeatpassword),
                        "email"    => $this->validateEmail($email),
                        "api"      => $this->validateAPI($apikey, $vcode),
                        "reports"  => $this->validateReports($reports),
        );

        if (!isset($result['username']) && !isset($result['password']) &&
            !isset($result['email']) && !isset($result['api']) && !isset($result['reports'])) {
            $characters         = $this->getCharacters($apikey, $vcode);
            $data['characters'] = $characters;
            $data['view']       = "register/register_characters_v";
            $data['apikey']     = $apikey;
            $data['vcode']      = $vcode;
            $data['no_header']  = 1;
            $this->twig->display('main/_template_v', $data);
        } else {
            $data['result']    = $result;
            $data['view']      = "register/register_v";
            $data['no_header'] = 1;
            $this->twig->display('main/_template_v', $data);
        }
    }

    /**
     * Begin registration operations and validations - step 2
     * @return void
     */
    public function processCharacters(): void
    {
        $userData = [
            'username'         => $this->input->post('username', true),
            'password'         => $this->input->post('password', true),
            'email'            => $this->input->post('email', true),
            'apikey'           => (int) $this->input->post('apikey', true),
            'vcode'            => $this->input->post('vcode', true),
            'reports'          => $this->input->post('reports'),
            'default_buy'      => $this->input->post('default-buy', true),
            'default_sell'     => $this->input->post('default-sell', true),
            'x_character'      => $this->input->post('x-character', true),
            'null_citadel_tax' => $this->input->post('null-citadel-tax', true),
            'null_station_tax' => $this->input->post('null-station-tax', true),
            'null_outpost_tax' => $this->input->post('null-outpost-tax', true),
            'null_buy_tax'     => $this->input->post('null-buy-tax', true),
            'null_sell_tax'    => $this->input->post('null-sell-tax', true),
        ];
        $chars = array();
        if ($char1 = $this->input->post('char1', true)) {
            array_push($chars, $char1);
        } else {
            $char1 = "";
        }
        if ($char2 = $this->input->post('char2', true)) {
            array_push($chars, $char2);
        } else {
            $char2 = "";
        }
        if ($char3 = $this->input->post('char3', true)) {
            array_push($chars, $char3);
        } else {
            $char3 = "";
        }

        $userData['chars'] = $chars;
        // no characters selected
        if (count($chars) == 0) {
            $data['characters'] = $this->getCharacters($userData['apikey'], $userData['vcode']);
            buildMessage("error", Msg::NO_CHARACTER_SELECTED);
            $data['characters'] = $characters;
            $data['view']       = "register/register_characters_v";
            $data['no_header']  = 1;

            $this->twig->display('main/_template_v', $data);
            return;
        }

        if ($this->verifyCharacters($userData['chars'], $userData['apikey'], $userData['vcode'])) {
            $result = $this->createAccount($userData);
            if (!$result['success']) {
                // failure creating account (sssion msg wont show on same page)
                buildMessage('error', $result['msg']);
                $data['characters'] = $this->getCharacters($userData['apikey'], $userData['vcode']);
                $data['view']       = "register/register_characters_v";
                $data['no_header']  = 1;
                $this->twig->display('main/_template_v', $data);
            } else {
                buildMessage('success', Msg::ACCOUNT_CREATE_SUCCESS);
                $data['SESSION']   = $_SESSION;
                $data['view']      = "login/login_v";
                $data['no_header'] = 1;
                $this->twig->display('main/_template_v', $data);
            }
        }
    }

    public function createAccount(array $data): array
    {
        $this->load->model('Api_keys_model', 'keys');
        $this->load->model('User_model', 'user');
        $this->load->model('Characters_model', 'characters');
        $this->load->model('Aggr_model', 'aggr');

        $error = "";
        $dt    = new DateTime();
        $tz    = new DateTimeZone('Europe/Lisbon');
        $dt->setTimezone($tz);
        $datetime = $dt->format('Y-m-d H:i:s');

        $this->load->library('Auth');
        $hashed = Auth::createHashedPassword($data['password']);

        $userData = array(
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

        $idUser = $this->user->insert($userData);
        $this->keys->insertIgnoreKeys($data['apikey'], $data['vcode']);

        foreach ($data['chars'] as $row) {
            $idCharacter     = (int) $row;
            $characterExists = $this->characters->getOne(['character_eve_idcharacter' => $idCharacter]);
            if ($characterExists) {
                $this->db->trans_rollback();
                $result['success'] = false;
                $result['msg']     = Msg::CHARACTER_ALREADY_TAKEN;
                return $result;
            }

            $pheal   = new Pheal($data['apikey'], $data['vcode'], "char"); //fetch character name
            $result  = $pheal->CharacterSheet(array("characterID" => $idCharacter));
            $configs = [
                'eve_idcharacter'  => $idCharacter,
                'name'             => $result->name,
                'balance'          => 0,
                'api_apikey'       => $data['apikey'],
                'networth'         => 0,
                'escrow'           => 0,
                'total_sell'       => 0,
                'broker_relations' => '0',
                'accounting'       => '0',
            ];

            $this->characters->insertUpdateCharacter($configs);
            $data_assoc = array(
                "user_iduser"               => $idUser,
                "character_eve_idcharacter" => $idCharacter,
            );
            $this->aggr->insert($data_assoc);
        }

        $data['success'] = true;
        return $data;
    }

    private function getCharacters($apikey, $vcode)
    {
        $pheal      = new Pheal($apikey, $vcode);
        $result     = $pheal->accountScope->APIKeyInfo();
        $characters = array();
        foreach ($result->key->characters as $character) {
            array_push($characters, array(
                array("name" => $character->characterName),
                array("id" => $character->characterID),
                )
            );
        }

        return $characters;
    }

    public function verifyCharacters(array $chars, int $apikey, string $vcode): bool
    {
        $pheal      = new Pheal($apikey, $vcode);
        $result     = $pheal->accountScope->APIKeyInfo();
        $apiChars  = array();
        $nameChars = array();
        $empty      = array();
        foreach ($result->key->characters as $character) {
            array_push($chars_api, $character->characterID);
            array_push($nameChars, $character->characterName);
        }

        // calculate differences between api result and selected characters and intersect the result
        if (array_intersect(array_diff($chars, $apiChars), $apiChars) != $empty) {
            return false;
        }
        return true;
    }

    /**
     * Apply all username related validations
     * @param  string $username
     * @return [type]
     */
    private function validateUsername(string $username)
    {
        if (!ValidateRequest::validateUsernameLength($username)) {
            return Msg::USERNAME_TOO_SHORT;
        }
        if (!ValidateRequest::validateUsernameAvailability($username)) {
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
        if (!ValidateRequest::validateEmailFormat($email)) {
            return Msg::INVALID_EMAIL;
        }
        if (!ValidateRequest::validateEmailAvailability($email)) {
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
        if (!ValidateRequest::validatePasswordLength($password)) {
            return Msg::PASSWORD_TOO_SHORT;
        }
        if (!ValidateRequest::validateIdenticalPasswords($password, $repeatpassword)) {
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
        return ValidateRequest::validateAPI($apikey, $vcode);
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
}
