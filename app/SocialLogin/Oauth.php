<?php

namespace App\SocialLogin;

interface Oauth {

    public function getLoginBaseUrl();
    public function getToken($code);
    public function getUserProfile($token);
    public function loginUser($email, $userId='', $displayName='');

}
