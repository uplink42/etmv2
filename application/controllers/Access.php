<?php
defined('BASEPATH') or exit('No direct script access allowed');

final class Access extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function index()
    {
        $code = $this->input->get('code');
        if (empty($code)) {
            return false;
        }

        $header = 'Authorization: Basic '.base64_encode(CLIENT_ID.':'.CLIENT_SECRET);
        $fields_string = '';
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $code
        ];
        
        foreach ($fields as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }

        rtrim($fields_string, '&');

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
        curl_setopt($ch, CURLOPT_USERAGENT, USERAGENT);
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
                curl_setopt($ch, CURLOPT_USERAGENT, USERAGENT);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [$header]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                $output = curl_exec($ch);
                $res = json_decode($output, 1);

                $params = [
                    'character' => $res['CharacterID'],
                    'refresh' => $refreshToken,
                    'token' => $accessToken,
                ];

                redirect('main/register?' . http_build_query($params));
            } else {
                show_404();
            }
        }

        show_404();
    }
}
