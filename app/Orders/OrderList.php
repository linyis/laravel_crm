<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderList extends Model
{
    protected $table = 'order_lists';

    protected $fillable = [
        'order_id', 'goods_id', 'quantity', 'total_price'
    ];

    public function goods() {
        return $this->hasOne(Goods::class,'goods_id','id');
    }

    public function orders() {
        return $this->belongsTo(Orders::class,'order_id','id');
    }
}
