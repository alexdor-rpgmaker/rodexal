<?php

namespace Database\Seeders;

use App\Word;
use Illuminate\Database\Seeder;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Word::class, 50)->create();
    }
}
