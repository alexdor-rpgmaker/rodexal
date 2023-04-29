<?php

namespace Tests\Feature\Api\V0;

use App\Former\Game;
use App\Former\JukeboxMusic;
use App\Former\Member;
use App\Former\Session;
use Tests\Feature\FeatureTest;

/**
 * @testdox JukeboxMusicApiRouter
 */
class JukeboxMusicApiRouterTest extends FeatureTest
{
    /**
     * @test
     * @testdox We can read the JSON list of Jukebox musics
     * On peut lire la liste en JSON des musiques du Jukebox
     */
    public function index_whenMusicsArePresent_returnsMusicsList()
    {
        $this->createSessionsAndGames();
        JukeboxMusic::factory()->create([
            'id' => 123,
            'id_jeu_origine' => 123,
            'titre' => 'B Music',
            'com' => 'This music is for B Game',
            'url_fichier' => 'b_game_zik',
        ]);
        JukeboxMusic::factory()->create([
            'id' => 234,
            'id_jeu_origine' => 234,
            'titre' => 'A Music',
            'com' => 'This music is for A Game',
            'url_fichier' => 'a_game_zik',
        ]);
        JukeboxMusic::factory()->create([
            'id' => 345,
            'titre' => 'Deleted Music',
            'statut_zik' => 0
        ]);

        $response = $this->get('/api/v0/musics');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', "A Music")
            ->assertJsonPath('data.0.description', "This music is for A Game")
            ->assertJsonPath('data.0.music_url', "https://www.alexdor.info/zik/mp3/a_game_zik.mp3")
            ->assertJsonPath('data.0.game.id', 234)
            ->assertJsonPath('data.0.game.title', "A Game")
            ->assertJsonPath('data.0.game.session', "2022")

            ->assertJsonPath('data.1.title', "B Music")
            ->assertJsonPath('data.1.description', "This music is for B Game")
            ->assertJsonPath('data.1.music_url', "https://www.alexdor.info/zik/mp3/b_game_zik.mp3")
            ->assertJsonPath('data.1.game.id', 123)
            ->assertJsonPath('data.1.game.title', "B Game")
            ->assertJsonPath('data.1.game.session', "2021");
    }

    /**
     * @return void
     */
    private function createSessionsAndGames(): void
    {
        Member::factory()->create();
        Session::factory()->create([
            'id_session' => 21,
        ]);
        Game::factory()->create([
            'id_jeu' => 123,
            'id_session' => 21,
            'nom_jeu' => 'B Game',
        ]);
        Session::factory()->create([
            'id_session' => 22,
        ]);
        Game::factory()->create([
            'id_jeu' => 234,
            'id_session' => 22,
            'nom_jeu' => 'A Game',
        ]);
    }
}
