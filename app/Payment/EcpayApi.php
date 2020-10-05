<?php
namespace App\Payment;

use App\Orders\Common\FormMaker;
use App\Payment\ThirdPartApi;
use App\Payment\CheckMacValue;
use App\Mail\ECPayOrderMail;
use Illuminate\Support\Facades\Mail;

class EcpayApi implements ThirdPartApi
{
    public function checkOut($dataAry) {
        $szCheckMacValue = CheckMacValue::generate($dataAry,'5294y06JbISpM5x9','v77hoKGq4kWxNNIS');
        $dataAry['CheckMacValue'] = $szCheckMacValue;
        FormMaker::make('https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5', $dataAry);
    }

    // public function callBack($request, $order) {
    //     $order->payment_type = 1;
    //     $order->status = 5;
    //     $order->end_time = $request->input('TradeDate');
    //     $order->platform_time = now();
    //     $order->save();
    //     $order->payinfo()->create([
    //         'pay_platform' => 'ECPay',
    //         'platform_number' => $request->input('MerchantTradeNo'),
    //         'platform_status' => json_encode($request->toArray())
    //     ]);
    // // 發送 email
    //     Mail::to($order->email)->queue(new ECPayOrderMail($request->input('TradeDate'), $request->input('MerchantTradeNo'), $order->payment ));
    // }
}
