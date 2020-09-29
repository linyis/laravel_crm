<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_no', 'payment', 'payment_type' ,'email' , 'mobile'
    ];

    public function orderList(){
        return $this->hasMany(Order_list::class,'order_id','id');
    }

    public function payinfo(){
        return $this->hasOne(PayInfo::class,'order_id','id');
    }
}
