<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model{

    /**
     * table db
     * @var string
     */
    protected $table = 'users_balance';

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
