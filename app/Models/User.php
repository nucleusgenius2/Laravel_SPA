<?php

namespace App\Models;


use App\Jobs\SendMail;
use App\Models\UserBalance;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * custom mutator - bcrypt pass
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * connect table users_balance
     */
    public function userBalance()
    {
         return $this->hasOne(UserBalance::class, 'id_user');
    }

    /**
     * connect table users_balance_operations
     */
    public function userBalanceOperations()
    {
        return $this->hasMany(UserBalanceOperations::class,'id_user');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            SendMail::dispatch($user,'registration')->onQueue('emails');
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
    }
}
