<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Word::class, 50)->create();
    }
}
