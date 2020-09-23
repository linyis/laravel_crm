<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Crm;
use App\Jobs\BrowserCount;
use App\Jobs\BrowserLog;
use App\Log;
use Illuminate\Support\Facades\Validator;
use App\Common\ResizeImage;

class CrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$crms = Crm::simplePaginate(15);
        if (request("search")) {
            $crms = Crm::where('subject','like','%'.$request['search'].'%')->paginate(10);
        } else {
            $crms = Crm::orderBy("created_at","DESC")->paginate(10);
        }

        return view('crms.index')->with(
            ['crms'=>$crms]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crms.create')->with(
            [
                'back'=>"crm.index"
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        $request['user_id'] = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'content' => 'required|string'
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors()->all();
            return back()->with('errors', $errors)->withInput();
        }

        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $crm = new Crm();
        if ($request->hasFile("image"))
        {

            $filename = time().'.'.$request->image->getClientOriginalExtension();
                  // Create unique file name

            $resize = new ResizeImage();
            $resize->load($request->image);
            //list($width, $height, $type, $attr) = getimagesize($request->image);
            if ($resize->getWidth()>2048)
            {
                $resize->resizeToWidth(2048);


            }
//            $resize->save(storage_path("images").$filename);
            $resize->save(public_path().'/storage/images/'.$filename);

            //$request->image->storeAs("images",$filename,"public");

            $crm->image = $filename;
        }

        $crm->subject=$request->subject;
        $crm->content=$request->content;
        $crm->user_id = auth()->user()->id;
        $crm->save();

//        Crm::create($request->all());
        return redirect("crm")->with('message',"Create Done!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Crm $crm)
    {
        return view('crms.detail')->with(
            [
                'crm'=>$crm,
                'back'=>"crm.index"
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crm $crm)
    {
        $crm->delete();
        return redirect("crm")->with('message',"Delete Done!");
    }
}
