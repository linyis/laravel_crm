<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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
        DB::transaction(function () use (&$order, $request) {

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

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5');
        // curl_setopt_array($ch, array(
        //     CURLOPT_POST => true,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_SSL_VERIFYPEER => true,
        //     CURLOPT_AUTOREFERER => true,
        //     CURLOPT_HEADER => false,
        //     CURLOPT_RETURNTRANSFER => true,
        // ));

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
//        $dataAry['CheckMacValue'] = $this->ecpayCheckMacValue($dataAry,'5294y06JbISpM5x9','v77hoKGq4kWxNNIS');
//        return dd($dataAry);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataAry));
        // $output = curl_exec($ch);
        // curl_close($ch);
        // return $output;

        //生成表單，自動送出
        $szCheckMacValue = $this->ecpayCheckMacValue($dataAry,'5294y06JbISpM5x9','v77hoKGq4kWxNNIS');

        $szHtml =  '<!DOCTYPE html>';
        $szHtml .= '<html>';
        $szHtml .=     '<head>';
        $szHtml .=         '<meta charset="utf-8">';
        $szHtml .=     '</head>';
        $szHtml .=     '<body>';
        $szHtml .=         "<form id=\"__ecpayForm\" method=\"post\" target=\"_self\" action=\"https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5\">";

        foreach ($dataAry as $keys => $value) {
            $szHtml .=         "<input type=\"hidden\" name=\"{$keys}\" value='{$value}' />";
        }

        $szHtml .=             "<input type=\"hidden\" name=\"CheckMacValue\" value=\"{$szCheckMacValue}\" />";
        $szHtml .=         '</form>';
        $szHtml .=         '<script type="text/javascript">document.getElementById("__ecpayForm").submit();</script>';
        $szHtml .=     '</body>';
        $szHtml .= '</html>';

        echo $szHtml ;

    }
    // 綠界 cehckout
//    public function checkout(Request $request, Order $order)
    public function checkout(Request $request, Order $order)
    {

        $order->payment_type = 1;
        $order->status = 5;
        $order->end_time = now();
        $order->platform_time = now();
        $order->save();
        $order->payinfo()->create([
            'pay_platform' => 'ECPay',
            'platform_number' => '',
            'platform_status' => ''
        ]);
        print_r($request);
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
