<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\SocialLogin\Line;
use GuzzleHttp\Client;

class LineLoginController extends Controller
{
    protected $lineService;

    public function __construct(Line $lineService)
    {
        $this->lineService = $lineService;
    }

    public function page()
    {
        $url = $this->lineService->getLoginBaseUrl();
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

            $response = $this->lineService->getLineToken($code);
            
            $user_profile = $this->lineService->getUserProfile($response['access_token']);

            echo "<pre>"; print_r($user_profile); echo "</pre>";
        } catch (Exception $ex) {

            echo $ex->getMessage();
            Log::error($ex);
        }

    }
}
