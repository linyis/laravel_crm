<?php

namespace App\SocialLogin;

class OauthFactory
{
    private $current;

    public function __construct(Oauth $setOauth)
    {
        $this->current = $setOauth;
    }

    public function getLoginBaseUrl()
    {
        return $this->current->getLoginBaseUrl();
    }

    public function getLineToken($code) {
        return $this->current->getToken($code);
    }
    public function getUserProfile($token){
        return $this->current->getUserProfile($token);
    }

    public function loginUser($email, $userId='', $displayName=''){
        return $this->current->loginUser($email, $userId, $displayName);
    }
}
