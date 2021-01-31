<?php

namespace Database\Seeders;

use App\Word;

use Illuminate\Database\Seeder;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        Word::factory()->count(50)->create();
    }
}
