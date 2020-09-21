<?php

namespace App\SocialLogin;

use Google_Client;

class Google
{

    public function getLoginBaseUrl()
    {
        $client = new \Google_Client;
        // 代入從 API console 下載下來的 client_secret
        $client->setAuthConfig('..\App\SocialLogin\client_google.json');
        
        // 加入需要的權限（Google Drive API）
        // 也可以使用 url，例如：https://www.googleapis.com/auth/drive.metadata.readonly
        $client->addScope(['profile', \Google_Service_Drive::DRIVE_METADATA_READONLY]);
        
        // 設定 redirect URI，登入認證完成會導回此頁
        $client->setRedirectUri('http://localhost:8000/google/callback');
        
        // 不需要透過使用者介面就可以 refresh token
        $client->setAccessType('offline');
        // 支援 Incremental Authorization 漸進式擴大授權範圍
        $client->setIncludeGrantedScopes(true);
        
        // 產生登入用的 URL
        $authUrl = $client->createAuthUrl();
        // 導至登入認證畫面
        return filter_var($authUrl, FILTER_SANITIZE_URL);
    }

    public function getToken($code)
    {
        $client = new Google_Client();
        $client->setAuthConfig('..\App\SocialLogin\client_google.json');
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/google/sendcode');
        $client->addScope(\Google_Service_Drive::DRIVE_METADATA_READONLY);
        
        if (! isset($_GET['code'])) {
          $auth_url = $client->createAuthUrl();
          return filter_var($auth_url, FILTER_SANITIZE_URL);

        } else {
          $client->authenticate($_GET['code']);
          $_SESSION['access_token'] = $client->getAccessToken();
          $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
          return filter_var($redirect_uri, FILTER_SANITIZE_URL);
        }
    }

    public function getAccess()
    {

    
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




