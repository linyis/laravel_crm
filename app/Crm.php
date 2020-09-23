<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Crm extends Model
{
    protected $fillable = [
        'subject', 'content', 'user_id', 'image', 'count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i');
    }
}
