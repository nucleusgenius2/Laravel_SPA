<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		Schema::create('limit_reset_passwords', function (Blueprint $table) {
            $table->id();
			$table->string('user_email');
			$table->string('user_ip');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::dropIfExists('limit_reset_passwords');
    }
};

