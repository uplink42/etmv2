<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/PHPMailer/PHPMailerAutoload.php';

final class OAuth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('esi_config');
    }

    public function index()
    {
    	header('Location: ' . 'https://login.eveonline.com/oauth/authorize/?response_type=code&redirect_uri=' . $this->config->item('esi_esi_callback_url')
                . '&client_id=' . $this->config->item('esi_client_id') . '&scope=' . $this->config->item('esi_scopes'));
    }

    public function authenticate()
    {
    	if (!$this->input->get('code')) {
    		echo 'Invalid Request';
    		return false;
    	}

    	$header = 'Authorization: Basic '.base64_encode($this->config->item('esi_client_id').':'.$this->config->item('esi_secret'));
        $fields_string = '';
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $this->input->get('code'),
        ];
        
        foreach ($fields as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config->item('user_agent'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch);
        $response = json_decode($output, 1);

        if (isset($response['access_token'])) {
            $accessToken = $response['access_token'];
            $refreshToken = $response['refresh_token'];
            curl_close($ch);
            if ($accessToken && $refreshToken) {
                // get character ID
                $header = 'Authorization: Bearer ' . $accessToken;
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/verify");
                curl_setopt($ch, CURLOPT_USERAGENT, $this->config->item('user_agent'));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [$header]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                $output = curl_exec($ch);
                $res = json_decode($output, 1);
                $params = [
                    'character' => $res['CharacterID'],
                    'refresh' => $refreshToken,
                    'token' => $accessToken,
                ];
                curl_close($ch);
                redirect('register?' . http_build_query($params));
            } else {
                show_404();
            }
        }
    }
}
