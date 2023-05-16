<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserBalanceOperations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BalancePlus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var float
     */
    protected float $number;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var string
     */
    protected string $operator;

    /**
     * Create a new job instance.
     */
    public function __construct(float $number, User $user)
    {
        $this->number = $number;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $balance = $this->user->userBalance->balance;

        $balance = $balance + $this->number;

        $this->user->userBalance->update([
            'balance' => $balance
        ]);

        UserBalanceOperations::create([
            'id_user' => $this->user->id,
            'name' => 'Пополнение баланса',
            'type' => 'plus',
            'balance' => $this->number
        ]);

    }
}
