<?php

namespace Database\Seeders;

use App\PodcastEpisode;

use Illuminate\Database\Seeder;

class PodcastEpisodeSeeder extends Seeder
{
    public function run()
    {
        PodcastEpisode::factory()->count(20)->create();
    }
}
