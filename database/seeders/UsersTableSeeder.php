<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Alex RuTiPa',
                'email' => 'equipe@alexdor.info',
                'password' => bcrypt('1234abcd5678'),
                'user_rank' => 6
            ],
            [
                'name' => Str::random(10),
                'email' => Str::random(10) . '@gmail.com',
                'password' => bcrypt('secret'),
                'user_rank' => 1
            ]
        ]);
    }
}
