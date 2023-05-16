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
        'id_user',
        'name',
        'balance',
        'type'
    ];

    public function userBalance()
    {
        return $this->belongsTo(UserBalance::class, 'id_user', 'id_user');
    }

}
