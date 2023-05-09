<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected string $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $notification)
    {
        $this->user = $user;
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dataEmail = [
            'email' => $this->user->email,
            'name' => $this->user->name,
            'type' => $this->notification,
        ];

        if ( $dataEmail['type'] === 'registration' ){
            $subject = 'Вы зарегистрированы на сайте ...';
            $dataEmail['text'] = 'Вы зарегистрированы';
        }

        Mail::send('mail', ['data' => $dataEmail], function ($message) use ($dataEmail, $subject) {
            $message->to($dataEmail['email'], $dataEmail['name'])->subject($subject);
            $message->from('FormiObratki@yandex.ru', 'RU FAF');
        } );
    }
}
