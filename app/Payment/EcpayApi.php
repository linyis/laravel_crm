<?php
namespace App\Payment;

use App\Orders\Common\FormMaker;
use App\Payment\ThirdPartApi;
use App\Payment\CheckMacValue;
use App\Mail\ECPayOrderMail;
use App\Orders\Order;
use App\Orders\OrderList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EcpayApi implements ThirdPartApi
{
    private $providerName = "ECPay";

    public function checkOut($request) {

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
            'ItemName' => $this->itemMerge($request),
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


        $szCheckMacValue = CheckMacValue::generate($dataAry,'5294y06JbISpM5x9','v77hoKGq4kWxNNIS');
        $dataAry['CheckMacValue'] = $szCheckMacValue;
        FormMaker::make('https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5', $dataAry);
    }

    private function itemMerge(&$request)
    {
        $str = "";
        $size = count($request->name);
        for ($i=0;$i<$size;$i++) {
            $str .= $request->name[$i];
            $str .= " ";
            $str .= $request->price[$i];
            $str .= " ";
            $str .= "元";
            $str .= "X";
            $str .= $request->quantity[$i];
            if ($i!=$size-1)
                $str .= "#";
        }
        return $str;
    }

    public function orderComplete($request, $order) {
        $order->payment_type = 1;
        $order->status = 5;
        $order->end_time = $request->input('TradeDate');
        $order->platform_time = now();
        $order->save();
        $order->payinfo()->create([
            'pay_platform' => $this->providerName,
            'platform_number' => $request->input('MerchantTradeNo'),
            'platform_status' => json_encode($request->toArray())
        ]);
    // 發送 email
        Mail::to($order->email)->queue(new ECPayOrderMail($request->input('TradeDate'), $request->input('MerchantTradeNo'), $order->payment ));
    }
}
