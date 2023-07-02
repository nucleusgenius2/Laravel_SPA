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
        //
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1); //макс 255
            $table->string('icons', 255)->nullable();
            $table->text('black_list')->nullable();
            $table->text('friend_list')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status');
            $table->string('icons' );
            $table->dropColumn('black_list');
            $table->dropColumn('friend_list');
        });
    }
};
