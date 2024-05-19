<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model{


    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'balance',
    ];


    /**
     * connect table users_balance_operations
     */
    public function userBalanceOperations()
    {
        return $this->hasMany(UserBalanceOperations::class,'user_id');
    }

}
