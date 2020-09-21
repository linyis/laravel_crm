<?php

namespace App\SocialLogin;

use GuzzleHttp\Client;

class Line
{

    public $channel_id = "1649018310";
    public $secret = "1f7cbab35d61178e6278c092f3edd84e";
    public $authorize_base_url = 'https://access.line.me/oauth2/v2.1/authorize';
    public $get_token_url = 'https://api.line.me/oauth2/v2.1/token';
    public $get_user_profile_url = 'https://api.line.me/v2/profile';


    public function getLoginBaseUrl()
    {
        // 組成 Line Login Url
        $url = $this->authorize_base_url.'?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . $this->channel_id;
        $url .= '&redirect_uri=' . config('app.url') . ':8000/line/callback';
        $url .= '&state=test'; // 暫時固定方便測試
        $url .= '&scope=openid%20profile';

        return $url;
    }

    public function getLineToken($code)
    {
        // $client = new Client();
        
        // $response = $client->request('POST', $this->get_token_url, [
        //     'form_params' => [
        //         'grant_type' => 'authorization_code',
        //         'code' => $code,
        //         'redirect_uri' => config('app.url') . ':8000/line/callback',
        //         'client_id' => $this->channel_id,
        //         'client_secret' => $this->secret
        //     ]
        // ]);

        // return json_decode($response->getBody()->getContents(), true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->get_token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt_array($ch, array(
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $data = http_build_query(array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('app.url') . ':8000/line/callback',
            'client_id' => $this->channel_id,
            'client_secret' => $this->secret
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($output, true);

    }

    public function getUserProfile($token)
    {
        // $headers = [
        //     'Authorization' => 'Bearer ' . $token,
        //     'Accept'        => 'application/json',
        // ];
        $headers = array(
            "Accept: application/json",
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Authorization: Bearer " . $token
        );

        // $client = new Client();
        // $response = $client->request('GET', $this->get_user_profile_url, [
        //     'headers' => $headers
        // ]);
        // return json_decode($response->getBody()->getContents(), true);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->get_user_profile_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($output);

    }
}




