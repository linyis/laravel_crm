<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

class ECPayOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $platform_time;
    public $name = 'ECPay';
    public $order_no = '';
    public $price = 0;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($time, $order_no, $price)
    {
        $this->queue = 'default';
        $this->platform_time = $time ?? Carbon::now();
        $this->order_no = $order_no ?? '';
        $this->price = $price ?? 0;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('linyis@gmail.com')->view('emails.OrderTpl');
    }
}
