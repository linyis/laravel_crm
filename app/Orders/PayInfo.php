<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Model;

class PayInfo extends Model
{
    protected $table = 'payinfos';
    protected $fillable = [
        'order_id'
    ];

    public function order() {
        return $this->belongsTo(Orders::class,'order_id','id');
    }
}
