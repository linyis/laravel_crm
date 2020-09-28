<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Crm;
use App\Jobs\BrowserCount;
use App\Jobs\BrowserLog;
use App\Log;
use Illuminate\Support\Facades\Validator;
use App\Common\ResizeImage;
use App\Orders\Goods;
use App\Orders\OrderList;
use App\Orders\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('order.index')->with([

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $goods = Goods::select("id as id", "name as name","price as price")->limit(5)->get();

        return view('order.create')->with(
            [
                'goods' => $goods,
                'back'=>"order.index"
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
        $osn = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8); // 訂單編號

        $order = new Order();
        $order->order_no = $osn;
        $order->email = $request->email;
        $order->mobile = $request->mobile;
        $order->payment = $request->total_price;
        $order->payment_type= 1;
        $order->status = 10;

        DB::transaction(function () use ($order, $request) {
            $order_id = $order->save();
            for ($i=0;$i<count($request->name);$i++)
            {
                $orderitem = new OrderList();
                $orderitem->order_id = $order_id;
                $orderitem->goods_id = $request->id[$i];
                $orderitem->quantity = $request->quantity[$i];
                $orderitem->total_price = $request->price[$i] * $request->quantity[$i];
                $orderitem->save();
            }
        }, 5);

        return 'ok';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
