<?php

namespace App\SocialLogin;

use App\SocialKey;
use Illuminate\Support\Facades\DB;

class Facebook implements Oauth
{

    private $channel_id;
    private $secret;
    private $providerName;
    private $authorize_base_url = 'https://www.facebook.com/v8.0/dialog/oauth';
    private $get_token_url = 'https://graph.facebook.com/v8.0/oauth/access_token';
    private $get_user_profile_url = 'https://api.line.me/v2/profile';

    public function __construct()
    {
        $this->providerName = "FACEBOOK";
        $data = DB::table("social_keys")->where("name","=",$this->providerName)->first();
        $this->channel_id = $data->channel;
        $this->secret = $data->key;
    }

    public function getLoginBaseUrl()
    {
        // 組成 Line Login Url
        $url = $this->authorize_base_url.'?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . $this->channel_id.'';
        $url .= '&redirect_uri=' . config('app.url') . ':8000/facebook/callback';
        $url .= '&state=test'; // 暫時固定
        $url .= '&scope=email';

        return $url;
    }

    public function getToken($code)
    {

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

    public function loginUser($email, $userId='', $displayName='') {

    }
}




