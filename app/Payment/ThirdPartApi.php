<?php

namespace App\Payment;

interface ThirdPartApi
{
    public function checkOut($request);
    public function orderComplete($request, $order);
}
