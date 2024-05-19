<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRights extends Model{


    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'rights'
    ];

}
