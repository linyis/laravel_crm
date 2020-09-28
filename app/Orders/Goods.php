<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'Goods';

    protected $fillable = [
        'name', 'price'
    ];

    public function orderList(){
        return $this->belongsToMany(Order_list::class, 'goods_id');
    }
}
