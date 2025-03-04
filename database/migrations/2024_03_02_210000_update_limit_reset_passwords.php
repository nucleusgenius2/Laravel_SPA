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
		Schema::table('limit_reset_passwords', function (Blueprint $table) {
            $table->dropColumn(['user_ip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('limit_reset_passwords', function (Blueprint $table) {
			$table->string('user_ip');
		});
    }
};
