<?php

namespace App\SocialLogin;

class OauthFactory
{
//    private $current;
    public static function makeOauth($providerName) {
        switch ($providerName)
        {
            case 'LINE':
                return new Line;
                break;
            case 'FACEBOOK':
                return new Facebook;
                break;
            case 'GOOGLE':
                return new Google;
                break;
            default:
                throw new \Exception('not support Providername!');
                break;
        }
    }

    // public function __construct(Oauth $setOauth)
    // {
    //     $this->current = $setOauth;
    // }

    // public function getLoginBaseUrl()
    // {
    //     return $this->current->getLoginBaseUrl();
    // }

    // public function getToken($code) {
    //     return $this->current->getToken($code);
    // }
    // public function getUserProfile($token){
    //     return $this->current->getUserProfile($token);
    // }

    // public function loginUser($email, $userId='', $displayName=''){
    //     return $this->current->loginUser($email, $userId, $displayName);
    // }
}
