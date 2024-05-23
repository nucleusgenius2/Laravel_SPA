<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::firstOrCreate([
            'id' => 1
        ],[
            'name' => 'Тестовая новость',
            'content' => 'Содержимое текстовой новости Содержимое текстовой новости Содержимое текстовой новости',
            'short_description' => 'Краткое содержимое текстовой новости',
            'seo_title' => 'Тестовая новость тайтл',
            'seo_description' => 'Тестовая новость дескрипшен',
            'img' => '/images/1680444267.png',
            'category_id' => 0,
            'author' => ''
        ]);

    }
}
