<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\SocialLogin\Facebook;
use App\SocialLogin\OauthFactory;


class FacebookLoginController extends Controller
{
    protected $service;

    public function __construct()
    {
         $this->service = new OauthFactory(new Facebook());
    }

    public function page()
    {

        $url = $this->service->getLoginBaseUrl();
        return $url;
        return redirect($url);
    }

    public function LoginCallBack(Request $request)
    {

        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception($request->all());
            }

            $code = $request->input('code', '');
            $response = $this->service->getToken($code);
            $userProfile = $this->service->getUserProfile($response['access_token']);

            echo "<pre>"; print_r($userProfile); echo "</pre>";
        } catch (Exception $ex) {

            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
