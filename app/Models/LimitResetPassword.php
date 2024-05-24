<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimitResetPassword extends Model
{
    protected $fillable = [
        'user_email',
        'user_ip',
    ];

}

