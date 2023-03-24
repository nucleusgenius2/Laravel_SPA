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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
			$table->text('name');
			$table->text('content');
			$table->text('short_description');
			$table->text('seo_title');
			$table->text('seo_discription');
			$table->text('img');
			$table->text('id_category');
			$table->text('autor');
			$table->text('autor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
