<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crm extends Model
{
    protected $fillable = [
        'subject', 'content', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
