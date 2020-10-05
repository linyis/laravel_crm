<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Log;
use App\Mail\ECPayOrderMail;
use App\Orders\Goods;
use App\Orders\OrderList;
use App\Orders\Order;
use App\Payment\CheckMacValue;
use Illuminate\Support\Facades\Mail;
use App\Orders\Common\FormMaker;
use App\Payment\EcpayApi;
use App\Payment\ThirdPartApiFactory;

class OrderController extends Controller
{
    /**
     * 訂單預設的使用第三方支付 Api
     *
     */
    protected $paymentApi;

    public function __construct()
    {
         $this->paymentApi = ThirdPartApiFactory::makeApi('ECPay');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('status',5)->get();
        return view('order.index')->with([
            'orders' => $orders
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
        // 檢查 $request
        $this->paymentApi->checkOut($request);
    }
    /**
     * 綠界 訂單完成回應
     */
    public function checkout(Request $request, Order $order)
    {
        $this->paymentApi->orderComplete($request, $order);

    // 導回主頁
        return redirect()->route('order.index')->with(['message'=>'訂單完成']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $id)
    {

//        return redirect()->route('order.index')->with(['message'=>'訂單完成']);
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
