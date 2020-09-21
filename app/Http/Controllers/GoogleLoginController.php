<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialLogin\Google;
use Google_client;

class GoogleLoginController extends Controller
{
    protected $service;

    public function __construct(Google $google)
    {
        $this->service = $google;
    }

    public function page()
    {
        $url = $this->service->getLoginBaseUrl();
        return redirect($url);
    }

    public function sendcode(Request $request)
    {
        try {
//        $user_profile = $this->lineService->getUserProfile($response['access_token']);

//        echo "<pre>"; print_r($user_profile); echo "</pre>";
        } catch (Exception $ex) {

            echo $ex->getMessage();
            Log::error($ex);
        }
    }

    public function LoginCallBack(Request $request)
    {

        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception($request->all());
            }

            $code = $request->input('code', '');

            $url = $this->service->getToken($code);
            echo $url;
//            redirect($url);
        } catch (Exception $ex) {

            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
