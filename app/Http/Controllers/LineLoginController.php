<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\SocialLogin\Line;
use App\SocialLogin\OauthFactory;
use GuzzleHttp\Client;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\SocialUser;
use Illuminate\Support\Facades\Auth;

class LineLoginController extends Controller
{
    protected $service;

    public function __construct()
    {
         $this->service = new OauthFactory(new Line());
    }

    public function page()
    {

        $url = $this->service->getLoginBaseUrl();
        return redirect($url);
    }

    public function LoginCallBack(Request $request)
    {
        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception($request->all());
            }
//  權杖
            $code = $request->input('code', '');

            $response = $this->service->getLineToken($code);
            $user_profile = $this->service->getUserProfile($response);

//  處理 Login 及帳號新增


            $login_user = $this->service->loginUser($user_profile->email, $user_profile->userId, $user_profile->displayName);


            if (!is_null($login_user)) {
                Auth::loginUsingId($login_user->id);
                return redirect("crm");
            }


            print_r($user_profile);


        } catch (Exception $ex) {
            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
