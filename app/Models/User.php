<?php

namespace App\Models;


use App\Jobs\SendMail;
use App\Models\UserBalance;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        'balance'
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
     * override class to send password recovery message
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * override class to send email confirmation message
     * @param $subject
     * @return void
     */
    public function sendEmailVerificationNotification($subject = 'Email Verification'): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->id,
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        $this->notify(new EmailVerificationNotification($verificationUrl));
    }


    /**
     * custom mutator - bcrypt pass
     * @param $value
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * connect table users_balance
     */
    public function userBalance(): HasOne
    {
         return $this->hasOne(UserBalance::class, 'user_id');
    }

    /**
     * connect table users_balance_operations
     */
    public function userBalanceOperations(): HasMany
    {
        return $this->hasMany(UserBalanceOperations::class,'user_id');
    }

    /*
     * отправка письма после регистрации юзера, произвольная реализация
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
    */
}
