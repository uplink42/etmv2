<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('msg');
        $this->load->helper('validation');
        $this->load->library('twig');
        $this->load->config('esi_config');
        $this->load->model('User_model', 'user');
        $this->load->model('Characters_model', 'characters');
        $this->load->model('Aggr_model', 'aggr');
        $this->load->model('Refresh_token_model', 'token');
    }

    public function index()
    {
        if (!$this->input->get('character') || !$this->input->get('refresh') || !$this->input->get('token')) {
            echo 'Invalid Request';
            return false;
        }

        $data['no_header']   = 1;
        $data['view']        = 'register/register_v';
        $data['characterID'] = $this->input->get('character');
        $data['refresh']     = $this->input->get('refresh');
        $data['token']       = $this->input->get('token');
        $this->twig->display('main/_template_v', $data);
    }

    public function processData(): void
    {
        $registrationData = array(
            'username'         => $this->input->post('username', true),
            'character'        => $this->input->post('character', true),
            'password'         => $this->input->post('password', true),
            'repeatpassword'   => $this->input->post('repeatpassword', true),
            'email'            => $this->input->post('email', true),
            'reports'          => $this->input->post('reports'),
            'characterID'      => $this->input->post('character', true),
            'token'            => $this->input->post('token', true),
            'refresh'          => $this->input->post('refresh', true),
            'default_buy'      => $this->input->post('default-buy', true),
            'default_sell'     => $this->input->post('default-sell', true),
            'x_character'      => $this->input->post('x-character', true),
            'null_citadel_tax' => $this->input->post('null-citadel-tax', true),
            'null_station_tax' => $this->input->post('null-station-tax', true),
            'null_outpost_tax' => $this->input->post('null-outpost-tax', true),
            'null_buy_tax'     => $this->input->post('null-buy-tax', true),
            'null_sell_tax'    => $this->input->post('null-sell-tax', true),
        );
        $result = array("username"  => $this->validateUsername($registrationData['username']),
                        "password"  => $this->validatePassword($registrationData['password'], $registrationData['repeatpassword']),
                        "character" => $this->validateCharacter($registrationData['character']),
                        "email"     => $this->validateEmail($registrationData['email']),
                        "reports"   => $this->validateReports($registrationData['reports']),
        );

        if (!isset($result['username']) && !isset($result['password']) &&
            !isset($result['email']) && !isset($result['character']) && !isset($result['reports'])) {
            $authentication = new \Seat\Eseye\Containers\EsiAuthentication([
                'client_id'     => $this->config->item('esi_client_id'),
                'secret'        => $this->config->item('esi_secret'),
                'refresh_token' => $registrationData['refresh'],
            ]);

            $esi = new \Seat\Eseye\Eseye($authentication);
            $character_info = $esi->invoke('get', '/characters/{character_id}/', [
                'character_id' => $registrationData['character'],
            ]);
            $registrationData['character_name'] = $character_info->name;
            
            $result = $this->createAccount($registrationData);
            if (!$result['success']) {
                // failure creating account (sssion msg wont show on same page)
                buildMessage('error', $result['msg']);
                $data['view']       = "register/register_v";
                $data['no_header']  = 1;
                $this->twig->display('main/_template_v', $data);
            } else {
                buildMessage('success', Msg::ACCOUNT_CREATE_SUCCESS);
                $data['SESSION']   = $_SESSION;
                $data['view']      = "login/login_v";
                $data['no_header'] = 1;
                $this->twig->display('main/_template_v', $data);
            }
        } else {
            // error
            $data['result']      = $result;
            $data['characterID'] = $registrationData['character'];
            $data['refresh']     = $registrationData['refresh'];
            $data['token']       = $registrationData['token'];
            $data['view']        = "register/register_v";
            $data['no_header']   = 1;
            $this->twig->display('main/_template_v', $data);
        }
    }

    public function createAccount(array $data): array
    {
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
        $idToken = $this->token->insert(array('token' => $data['refresh']));

        $configs = [
            'eve_idcharacter'  => $data['character'],
            'name'             => $data['character_name'],
            'balance'          => 0,
            'networth'         => 0,
            'escrow'           => 0,
            'total_sell'       => 0,
            'broker_relations' => '0',
            'accounting'       => '0',
            'refresh_token_id' => $idToken,
        ];
        $idCharacter = $this->characters->insertOrUpdate($configs);

        $data_assoc = array(
            "user_iduser"               => $idUser,
            "character_eve_idcharacter" => $idCharacter,
        );
        $this->aggr->insert($data_assoc);
        
        $data['success'] = true;
        return $data;
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

    private function validateCharacter($idCharacter)
    {
        if (!ValidateRequest::validateCharacterAvailability($idCharacter)) {
            return Msg::CHARACTER_ALREADY_TAKEN;
        }
    }
}
