<?php

namespace Database\Factories;

use App\User;
use App\PodcastEpisode;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PodcastEpisodeFactory extends Factory
{
    protected $model = PodcastEpisode::class;

    public function definition(): array
    {
        $date = $this->faker->date();
        $label = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $this->faker->unique()->colorName);

        $author = User::inRandomOrder()->first();
        $poster = User::inRandomOrder()->first();

        return [
            'id' => $this->faker->uuid(),
            'title' => $label,
            'slug' => Str::slug($label, '-'),
            'season' => $this->faker->numberBetween(1, 3),
            'number' => $this->faker->numberBetween(1, 30),
            'description' => $this->faker->unique()->paragraph(),
            'audio_url' => self::randomSamplePodcastUrl(),
            'duration_in_seconds' => mt_rand(200, 2000),
            'author_id' => $author ? $author->id : null,
            'poster_id' => $poster ? $poster->id : null,
            'created_at' => $date,
            'updated_at' => $date
        ];
    }

    private function randomSamplePodcastUrl(): string
    {
        // Sample episodes from the awesome podcast: https://www.cafe-craft.fr/
        $podcastFileId = Arr::random([
            '8c136279-c9a3-4a8d-a995-21fa441fc2b1',
            '749224d9-4a44-4d4d-87af-afee51801401',
            '1573ec84-fa51-4605-95cb-ddac5e21bc31',
            '70feb539-00e0-419d-8294-636df1a13e18'
        ]);
        return "https://aphid.fireside.fm/d/1437767933/0ecc8b0c-4ccb-4785-abd9-78c19f1f12e7/$podcastFileId.mp3";
    }
}
