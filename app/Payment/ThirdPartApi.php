<?php

namespace App\Payment;

interface ThirdPartApi
{
    public function checkOut($dataAry);
//    public function callBack($request, $order);
}
