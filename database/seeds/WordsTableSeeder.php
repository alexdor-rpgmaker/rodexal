<?php

use App\User;
use Illuminate\Database\Seeder;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Word::class, 50)->create();
    }
}
