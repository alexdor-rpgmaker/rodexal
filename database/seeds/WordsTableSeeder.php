<?php

use App\User;
use Illuminate\Database\Seeder;

class WordsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create('fr_FR');

        $array = array();
        for ($i=0; $i < 30; $i++) {
            $name = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $faker->unique()->colorName);
            $array []= [
                'label' => $name,
                'slug' => str_slug($name, '-'),
                'user_id' => User::first()->id,
                'description' => $faker->unique()->paragraphs(mt_rand(1, 3), true)
            ];
        }

        DB::table('words')->insert($array);
    }
}
