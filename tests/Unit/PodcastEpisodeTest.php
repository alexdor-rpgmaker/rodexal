<?php

namespace Tests\Unit;

use App\PodcastEpisode;
use Tests\TestCase;

/**
 * @testdox PodcastEpisode
 */
class PodcastEpisodeTest extends TestCase
{
    /**
     * @test
     * @param int    $durationInSeconds
     * @param string $expectedDuration
     * @testdox duration - If duration is $durationInSecondss, then display $expectedDuration
     * Si la durée est de $durationInSeconds en secondes, alors afficher $expectedDuration
     * @testWith        [1, "0:01"]
     *                  [10, "0:10"]
     *                  [60, "1:00"]
     *                  [100, "1:40"]
     *                  [120, "2:00"]
     *                  [200, "3:20"]
     */
    public function duration_severalCases($durationInSeconds, $expectedDuration)
    {
        $podcastEpisode = PodcastEpisode::factory()->make([
            'duration_in_seconds' => $durationInSeconds
        ]);

        $actualDuration = $podcastEpisode->duration();

        $this->assertEquals($expectedDuration, $actualDuration);
    }
}
