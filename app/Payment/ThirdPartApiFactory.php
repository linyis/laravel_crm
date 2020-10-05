<?php
namespace App\Payment;

class ThirdPartApiFactory
{
//    private $current;
    public static function makeApi($providerName) {
        switch ($providerName)
        {
            case 'ECPay':
                return new EcpayApi;
                break;
            default:
                throw new \Exception('not support Providername!');
                break;
        }
    }
}
