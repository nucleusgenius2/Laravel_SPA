<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalanceOperations extends Model{

    /**
     * table db
     * @var string
     */
    protected $table = 'users_balance_operations';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'balance',
        'type'
    ];

    public function userBalance()
    {
        return $this->belongsTo(UserBalance::class, 'user_id', 'user_id');
    }

}
