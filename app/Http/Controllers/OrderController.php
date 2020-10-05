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

class OrderController extends Controller
{
    /**
     * 訂單預設的使用第三方支付 Api
     *
     */
    protected $paymentApi;

    public function __construct()
    {
         $this->paymentApi = new EcpayApi();
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

        $osn = FormMaker::orderId();
        $order = new Order();
        $order->order_no = $osn;
        $order->email = $request->email;
        $order->mobile = $request->mobile;
        $order->payment = $request->total_price;
        $order->payment_type= 1;
        $order->status = 10;
        $order_id = null;
        DB::transaction(function () use (&$order, $request) {
//  儲存訂單資料 + 訂單詳細內容清單
            $order->save();

            for ($i=0;$i<count($request->name);$i++)
            {
                $orderitem = new OrderList();
                $orderitem->order_id = $order->id;
                $orderitem->goods_id = $request->id[$i];
                $orderitem->quantity = $request->quantity[$i];
                $orderitem->total_price = $request->price[$i] * $request->quantity[$i];
                $orderitem->save();
            }
        }, 5);
//  建立綠界基本 API 串接資料
        $dataAry = array(
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'ecPay1234'.str_random(10),
            'StoreID' => '00001', // not required
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $request->total_price,
            'TradeDesc' => urlencode('ecpay 商城購物'),
            'ItemName' => '手機 20 元 X2#隨身碟 60 元 X1',
            'ReturnURL' => route("order.checkout",["id"=>$order->id]),
            'OrderResultURL' => route("order.checkout",["id"=>$order->id]),
            'NeedExtraPaidInfo'=>'N',
            'ChoosePayment' => 'Credit',
            'EncryptType' => '1',
            "DeviceSource" => '',
            "IgnorePayment" => '',
            "PlatformID" => '',
            "CreditInstallment" => 0,
            "InstallmentAmount" => 0,
            "Redeem"            => 'Y',
            "UnionPay"          => 0,
            "Language"          => '',
            'InvoiceMark'       => 'N',
            'BindingCard'       => 0
        );

        $this->paymentApi->checkOut($dataAry);

    }
    // 綠界 訂單完成回應
    public function checkout(Request $request, Order $order)
    {
 //       $this->paymentApi->callBack($request, $order);

        $order->payment_type = 1;
        $order->status = 5;
        $order->end_time = $request->input('TradeDate');
        $order->platform_time = now();
        $order->save();
        $order->payinfo()->create([
            'pay_platform' => 'ECPay',
            'platform_number' => $request->input('MerchantTradeNo'),
            'platform_status' => json_encode($request->toArray())
        ]);
    // 發送 email
        Mail::to($order->email)->queue(new ECPayOrderMail($request->input('TradeDate'), $request->input('MerchantTradeNo'), $order->payment ));

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
