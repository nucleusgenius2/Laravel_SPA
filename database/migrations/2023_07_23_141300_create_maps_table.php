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
        Schema::create('maps', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('url_img', 255);
			$table->string('url_name', 255);
            $table->string('name', 100);
			$table->string('author', 100);
			$table->integer('author_id');
			$table->integer('version');
			$table->integer('total_player')->comment('количество игроков');
			$table->integer('rate')->comment('рейтинговая 1, не рейт 0');
			$table->string('size', 100)->comment('размер карты, например 20 на 20');
			$table->text('ch')->comment('хеш файлов карты');
			$table->integer('map_rate')->comment('рейтинг карты');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
