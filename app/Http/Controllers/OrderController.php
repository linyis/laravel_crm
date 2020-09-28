<?php
namespace App\Http\Controllers;

use Exception;
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
    function ecpayCheckMacValue(array $params, $hashKey='', $hashIV='', $encType = 1)
    {
    // 0) 如果資料中有 null，必需轉成空字串
        $params = array_map('strval', $params);

        // 1) 如果資料中有 CheckMacValue 必需先移除
        unset($params['CheckMacValue']);

        // 2) 將鍵值由 A-Z 排序
        uksort($params, 'strcasecmp');

        // 3) 將陣列轉為 query 字串
        $paramsString = urldecode(http_build_query($params));

        // 4) 最前方加入 HashKey，最後方加入 HashIV
        $paramsString = "HashKey={$hashKey}&{$paramsString}&HashIV={$hashIV}";

        // 5) 做 URLEncode
        $paramsString = urlencode($paramsString);

        // 6) 轉為全小寫
        $paramsString = strtolower($paramsString);

        // 7) 轉換特定字元
        $paramsString = str_replace('%2d', '-', $paramsString);
        $paramsString = str_replace('%5f', '_', $paramsString);
        $paramsString = str_replace('%2e', '.', $paramsString);
        $paramsString = str_replace('%21', '!', $paramsString);
        $paramsString = str_replace('%2a', '*', $paramsString);
        $paramsString = str_replace('%28', '(', $paramsString);
        $paramsString = str_replace('%29', ')', $paramsString);

        // 8) 進行編碼
        $paramsString = $encType ? hash('sha256', $paramsString) : md5($paramsString);

        // 9) 轉為全大寫後回傳
        return strtoupper($paramsString);
    }

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
        $order_id = null;
        // DB::transaction(function () use ($order, $request) {
        //     $order_id = $order->save();
        //     for ($i=0;$i<count($request->name);$i++)
        //     {
        //         $orderitem = new OrderList();
        //         $orderitem->order_id = $order_id;
        //         $orderitem->goods_id = $request->id[$i];
        //         $orderitem->quantity = $request->quantity[$i];
        //         $orderitem->total_price = $request->price[$i] * $request->quantity[$i];
        //         $orderitem->save();
        //     }
        // }, 5);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5');
        curl_setopt_array($ch, array(
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        $dataAry = array(
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'ecPay1234',
            'StoreID' => '00001', // not required
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $request->total_price,
            'TradeDesc' => 'ecpay 商城購物',
            'ItemName' => '手機 20 元 X2#隨身碟 60 元 X1',
            'ReturnURL' => route("order.checkout",["id"=>$order_id]),
            'NeedExtraPaidInfo'=>'Y',
            'ChoosePayment' => 'Credit',
            'CheckMacValue' => '',
            "DeviceSource" => '',
            "IgnorePayment" => '',
            "PlatformID" => '',
            'ClientBackURL' => route("order.index"),
            'EncryptType' => '1',
            "CreditInstallment" => 0,
            "InstallmentAmount" => 0,
            "Redeem"            => FALSE,
            "UnionPay"          => FALSE,
            "Language"          => '',
            "PeriodAmount"      => '',
            "PeriodType"        => '',
            "Frequency"         => '',
            "ExecTimes"         => '',
            "PeriodReturnURL"   => ''
        );
        $dataAry['CheckMacValue'] = $this->ecpayCheckMacValue($dataAry,'5294y06JbISpM5x9','v77hoKGq4kWxNNIS');
    //    return dd($dataAry);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataAry));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;

    }
    // 綠界 cehckout
    public function checkout($id)
    {
        //
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
