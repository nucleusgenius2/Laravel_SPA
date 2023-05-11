<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Jobs\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ListenersUserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLogin $event): void
    {
        SendMail::dispatch($event->user,'login')->onQueue('emails');
    }
}
