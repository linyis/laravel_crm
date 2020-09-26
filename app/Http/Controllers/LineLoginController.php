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
//            $code = $request->input('code', '');


            $response = $this->service->getLineToken($request->input('code', ''));
            $user_profile = $this->service->getUserProfile($response);


//  處理 Login 及帳號新增
            $login_user = null;
            $user = User::where("email","=",$user_profile->email)->where('provider','=','Line')->first();

            if ($user->count()>0){
                $login_user = $user;
            } else {
                $new_user = new User();
                $new_user->email = $user_profile->email;
                $new_user->name = $user_profile->displayName;
                $new_user->password = Hash::make(str_random(8));
                $new_user->provider = "Line";
                $new_user->save();
                $new_SocialUser = new SocialUser();
                $new_SocialUser->user_id = $new_user->id;
                $new_SocialUser->provider_user_id = $user_profile->userId;
                $new_SocialUser->provider = "Line";
                $new_SocialUser->save();
                $login_user = $new_user;
            }
            if (!is_null($login_user)) {
                Auth::loginUsingId($login_user->id);
//                Auth::login($login_user);
                return redirect("crm");
            }


            // if (!is_null($login_user))
            // {
            //     Auth::login($login_user);
            //     return redirect()->action('${CrmController@index}', ['parameterKey' => 'value']);
            // }
            print_r($user_profile);
            //echo "<pre>"; print_r($user_profile); echo "</pre>";

        } catch (Exception $ex) {
            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
