<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'id' => 1
        ],[
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => '123456',
            'status' => 2
        ]);

        User::firstOrCreate([
            'id' => 2
        ],[
            'name' => 'user',
            'email' => 'user@mail.ru',
            'password' => '123456',
            'status' => 1
        ]);

    }
}
