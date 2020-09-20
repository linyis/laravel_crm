<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'student_details';
    protected $fillable = [
        'ip',
    ];
}
