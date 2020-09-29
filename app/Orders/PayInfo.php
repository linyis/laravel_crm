<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class PayInfo extends Model
{
    protected $table = 'payinfos';
    protected $fillable = [
        'order_id', 'pay_platform', 'platform_number', 'platform_status'
    ];

    public function order() {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
