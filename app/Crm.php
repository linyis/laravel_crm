<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Crm extends Model
{
    protected $table = 'crms';

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

    public function categories(){
        return $this->belongsToMany(Category::class,'category_crm','crm_id','category_id');
    }
}
