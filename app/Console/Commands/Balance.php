<?php

namespace App\Console\Commands;

use App\Jobs\BalanceMinus;
use App\Jobs\BalancePlus;
use App\Jobs\SendMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Balance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance {email} {operator} {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Изменяет баланс юзера в большую или меньшую сторону';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $user = User::where('email', $this->argument('email' ))->first();

        $number = $this->argument('number' );

        $operator = $this->argument('operator' );

        if ( $operator === '+'){
            BalancePlus::dispatch($number, $user)->onQueue('balance');
        }
        if ( $operator === '-'){
            BalanceMinus::dispatch($number, $user)->onQueue('balance');
        }
    }
}
