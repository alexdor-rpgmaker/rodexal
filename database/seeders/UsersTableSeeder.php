<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Alex RuTiPa',
                'email' => 'equipe@alexdor.info',
                'password' => bcrypt('1234abcd5678'),
                'rank' => 6
            ],
            [
                'name' => Str::random(10),
                'email' => Str::random(10) . '@gmail.com',
                'password' => bcrypt('secret'),
                'rank' => 1
            ]
        ]);
    }
}
