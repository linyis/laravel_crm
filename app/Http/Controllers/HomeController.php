<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Crm;
use App\Jobs\BrowserCount;
use App\Jobs\BrowserLog;
use App\Log;
use App\SocialLogin\Line;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //$crms = Crm::simplePaginate(15);
        if (request("search")) {
            $crms = Crm::where('subject','like',$request['search'])->paginate(10);
        } else {
            $crms = Crm::paginate(10);
        }


        return view('home')->with(
            ['crms'=>$crms]
        );
    }
    public function detail(Crm $crm) {

        // $this->dispatch(new BrowserCount($crm));
        // $this->dispatch(new BrowserLog());

        return view('crms.detail')->with(
            ['crm'=>$crm]
        );
    }
}
