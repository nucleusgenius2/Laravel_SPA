<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_balance_operations', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
			$table->string('name', 255)->comment('Название операции');
			$table->string('type', 50)->comment('Тип операции');
			$table->decimal('balance', 10, 3)->comment('изменение баланса юзера');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_balance_operations');
    }
};
