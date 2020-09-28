<?php

namespace App\SocialLogin;

use App\SocialKey;
use Illuminate\Support\Facades\DB;
use App\User;
use App\SocialUser;
use Illuminate\Support\Facades\Hash;

class Line implements Oauth
{

    private $channel_id;
    private $secret;
    private $authorize_base_url = 'https://access.line.me/oauth2/v2.1/authorize';
    private $get_token_url = 'https://api.line.me/oauth2/v2.1/token';
    private $get_user_profile_url = 'https://api.line.me/v2/profile';
    private $verify_id = 'https://api.line.me/oauth2/v2.1/verify';

    public function __construct()
    {
        $data = DB::table("social_keys")->where("name","=","LINE")->first();
        $this->channel_id = $data->channel;
        $this->secret = $data->key;
    }

    public function getLoginBaseUrl()
    {
        // 組成 Line Login Url
        $url = $this->authorize_base_url.'?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . $this->channel_id;
        $url .= '&redirect_uri=' . config('app.url') . ':8000/line/callback';
        $url .= '&state=test'; // 暫時固定
        $url .= '&scope=openid%20profile%20email';

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

    public function getUserProfile($response)
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
            "Authorization: Bearer " . $response['access_token']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->get_user_profile_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $userProfile = json_decode($output);

// -------------------------------- verify id

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->verify_id);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt_array($ch, array(
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $data = http_build_query(array(
            'id_token' => $response['id_token'],
            'client_id' => $this->channel_id,
//            'nonce' => 'nonce',
            'user_id' => $userProfile->userId
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        $emailProfile = json_decode($output);
        $userProfile->email = $emailProfile->email;

        return $userProfile;

    }
}




