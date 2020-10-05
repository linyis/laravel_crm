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
            $response = $this->service->getToken($code);
            $userProfile = $this->service->getUserProfile($response);

//  處理 Login 及帳號新增
            $loginUser = $this->service->loginUser($userProfile->email, $userProfile->userId, $userProfile->displayName);

//  重導登入使用者
            if (!is_null($loginUser)) {
                Auth::loginUsingId($loginUser->id);
                return redirect("crm");
            }


            print_r($userProfile);


        } catch (Exception $ex) {
            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
