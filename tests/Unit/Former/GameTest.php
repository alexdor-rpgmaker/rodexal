<?php

namespace Tests\Unit\Former;

use App\Former\Game;
use App\Former\Session;

use Tests\TestCase;

/**
 * @testdox Game
 */
class GameTest extends TestCase
{
    /**
     * @test
     * @param int    $sessionStep
     * @param int    $gameStatusNumber
     * @param string $gameStatus
     * @testdox getStatus - If session's step is $sessionStep and game status number is $gameStatusNumber, the status is $gameStatus
     * Si la session est à l'étape $sessionStep et que le jeu a le statut numéro $gameStatusNumber, son statut est $gameStatus
     * @testWith        [1, 0, "deleted"]
     *                  [2, 1, "applied"]
     *                  [3, 1, "not_qualified"]
     *                  [3, 2, "qualified"]
     *                  [4, 2, "not_nominated"]
     *                  [4, 3, "nominated"]
     *                  [5, 3, "not_awarded"]
     *                  [5, 4, "awarded"]
     */
    public function getStatus_severalCases($sessionStep, $gameStatusNumber, $gameStatus)
    {
        $game = Game::factory()->create([
            'statut_jeu' => $gameStatusNumber,
            'id_session' => Session::factory()->create([
                'etape' => $sessionStep,
            ]),
        ]);

        $this->assertEquals($gameStatus, $game->getStatus());
    }

    /**
     * @test
     * @param string $distantLink
     * @param string $localLink
     * @param string $expected
     * @testdox getLogoUrl - If game's distant logo is $distantLink, local logo is $localLink, returns $expected
     * Si le le jeu a pour logo distant $distantLink, pour logo local $localLink, retourne $expected
     * @testWith        ["http://mygame.com/logo.gif", "logo.gif", "http://alex-dor.test/uploads/logos/logo.gif"]
     *                  ["http://mygame.com/logo.gif", "", "http://mygame.com/logo.gif"]
     *                  ["", "", ""]
     */
    public function getLogoUrl_severalCases($distantLink, $localLink, $expected)
    {
        $game = Game::factory()->make([
            'logo' => $localLink,
            'logo_distant' => $distantLink
        ]);

        $this->assertEquals($expected, $game->getLogoUrl());
    }

    /**
     * @test
     * @param string $distantLink
     * @param string $localLink
     * @param bool   $badLink
     * @param bool   $removedLink
     * @param bool   $expected
     * @testdox hasWindowsDownloadLink - If game's distant link is $distantLink, local link is $localLink, distant link is bad is $badLink and link is removed is $removedLink, returns $expected
     * Si le le jeu a pour lien distant $distantLink, pour lien local $localLink, mauvais lien distant est $badLink et lien retiré est $removedLink, retourne $expected
     * @testWith        ["http://dl.com/jeu.rar", "jeu-local.rar", false, false, true]
     *                  ["", "jeu-local.rar", false, false, true]
     *                  ["http://dl.com/jeu.rar", "", false, false, true]
     *                  ["", "", false, false, false]
     *                  ["http://dl.com/jeu.rar", "jeu-local.rar", true, false, true]
     *                  ["http://dl.com/jeu.rar", "", true, false, false]
     *                  ["http://dl.com/jeu.rar", "jeu-local.rar", false, true, false]
     */
    public function hasWindowsDownloadLink_severalCases($distantLink, $localLink, $badLink, $removedLink, $expected)
    {
        $game = Game::factory()->make([
            'lien' => $distantLink,
            'lien_sur_site' => $localLink,
            'is_lien_errone' => $badLink,
            'link_removed_on_author_demand' => $removedLink
        ]);

        $this->assertEquals($expected, $game->hasWindowsDownloadLink());
    }

    /**
     * @test
     * @param string $distantLink
     * @param string $localLink
     * @param bool   $badLink
     * @param bool   $removedLink
     * @param bool   $expected
     * @testdox hasMacDownloadLink - If game's distant link is $distantLink, local link is $localLink, distant link is bad is $badLink and link is removed is $removedLink, returns $expected
     * Si le le jeu a pour lien distant $distantLink, pour lien local $localLink, mauvais lien distant est $badLink et lien retiré est $removedLink, retourne $expected
     * @testWith        ["http://dl.com/jeu.rar", "jeu-local.rar", false, false, true]
     *                  ["", "jeu-local.rar", false, false, true]
     *                  ["http://dl.com/jeu.rar", "", false, false, true]
     *                  ["", "", false, false, false]
     *                  ["http://dl.com/jeu.rar", "jeu-local.rar", true, false, true]
     *                  ["http://dl.com/jeu.rar", "", true, false, false]
     *                  ["http://dl.com/jeu.rar", "jeu-local.rar", false, true, false]
     */
    public function hasMacDownloadLink_severalCases($distantLink, $localLink, $badLink, $removedLink, $expected)
    {
        $game = Game::factory()->make([
            'lien_sur_mac' => $distantLink,
            'lien_sur_site_sur_mac' => $localLink,
            'is_lien_errone' => $badLink,
            'link_removed_on_author_demand' => $removedLink
        ]);

        $this->assertEquals($expected, $game->hasMacDownloadLink());
    }

    /**
     * @test
     * @param string $distantLink
     * @param string $localLink
     * @param string $expected
     * @testdox getWindowsDownloadLink - If game's distant link is $distantLink, local link is $localLink, returns $expected
     * Si le le jeu a pour lien distant $distantLink, pour lien local $localLink, retourne $expected
     * @testWith        ["http://dl.com/jeu.rar", "jeu-local.rar", "http://alex-dor.test/archives/2019/jeux/jeu-local.rar"]
     *                  ["http://dl.com/jeu.rar", "", "http://dl.com/jeu.rar"]
     *                  ["", "", ""]
     */
    public function getWindowsDownloadLink_severalCases($distantLink, $localLink, $expected)
    {
        $game = Game::factory()->make([
            'id_session' => 19,
            'lien' => $distantLink,
            'lien_sur_site' => $localLink
        ]);

        $this->assertEquals($expected, $game->getWindowsDownloadLink());
    }

    /**
     * @test
     * @param string $distantLink
     * @param string $localLink
     * @param string $expected
     * @testdox getMacDownloadLink - If game's distant link is $distantLink, local link is $localLink, returns $expected
     * Si le le jeu a pour lien distant $distantLink, pour lien local $localLink, retourne $expected
     * @testWith        ["http://dl.com/jeu.rar", "jeu-local.rar", "http://alex-dor.test/archives/2019/jeux/jeu-local.rar"]
     *                  ["http://dl.com/jeu.rar", "", "http://dl.com/jeu.rar"]
     *                  ["", "", ""]
     */
    public function getMacDownloadLink_severalCases($distantLink, $localLink, $expected)
    {
        $game = Game::factory()->make([
            'id_session' => 19,
            'lien_sur_mac' => $distantLink,
            'lien_sur_site_sur_mac' => $localLink
        ]);

        $this->assertEquals($expected, $game->getMacDownloadLink());
    }
}
