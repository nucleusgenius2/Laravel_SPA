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
        Schema::create('mods', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('url_img', 255);
			$table->string('url_name', 255);
            $table->string('name', 100);
			$table->string('description', 255);
			$table->string('author', 100);
			$table->integer('author_id');
			$table->integer('version');
			$table->integer('type')->comment('1 UI, 0 sim mod');
			$table->text('ch')->comment('хеш файлов мода');
			$table->integer('mod_rate')->comment('рейтинг мода');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mods');
    }
};
