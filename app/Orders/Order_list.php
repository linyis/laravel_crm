<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class Order_list extends Model
{
    protected $table = 'order_lists';

    protected $fillable = [
        'order_id', 'goods_id', 'quantity', 'total_price'
    ];

    public function goods() {
        $this->hasOne(Goods::class,'goods_id','id');
    }
}
