<?php

namespace Tests\Unit\Former;

use Carbon\Carbon;
use Tests\TestCase;

use App\Former\Session;

/**
 * @testdox Session
 */
class SessionTest extends TestCase
{
    /**
     * @test
     * @param int $sessionStep
     * @param bool $expected
     * @testdox allowsGamesRegistration - If session step is $sessionStep, is it possible to register game ? $expected
     * Si l'étape de la session est $sessionStep, est-il possible d'inscrire un jeu ? $expected
     * @testWith        [0, false]
     *                  [1, true]
     *                  [2, false]
     *                  [3, false]
     *                  [4, false]
     */
    public function allowsGamesRegistration(int $sessionStep, bool $expected)
    {
        $session = Session::factory()->make([
            'etape' => $sessionStep
        ]);
        $actualAllowsGamesRegistration = $session->allowsGamesRegistration();

        $this->assertEquals($expected, $actualAllowsGamesRegistration);
    }

    /**
     * @test
     * @param int $sessionStep
     * @param bool $expected
     * @testdox tooLateForGamesRegistration - If session step is $sessionStep, is it too late for game registration ? $expected
     * Si l'étape de la session est $sessionStep, est-il trop tard pour l'inscription d'un jeu ? $expected
     * @testWith        [0, false]
     *                  [1, false]
     *                  [2, true]
     *                  [3, true]
     *                  [4, true]
     */
    public function tooLateForGamesRegistration(int $sessionStep, bool $expected)
    {
        $session = Session::factory()->make([
            'etape' => $sessionStep
        ]);
        $actualTooLateForGamesRegistration = $session->tooLateForGamesRegistration();

        $this->assertEquals($expected, $actualTooLateForGamesRegistration);
    }

    /**
     * @test
     * @param int $sessionStep
     * @param bool $expected
     * @testdox preTestsAreFinished - If session step is $sessionStep, are pre-tests finished ? $expected
     * Si l'étape de la session est $sessionStep, est-ce que les pré-tests sont finis ? $expected
     * @testWith        [0, false]
     *                  [1, false]
     *                  [2, false]
     *                  [3, true]
     *                  [4, true]
     */
    public function preTestsAreFinished(int $sessionStep, bool $expected)
    {
        $session = Session::factory()->make([
            'etape' => $sessionStep
        ]);
        $actualPreTestsAreFinished = $session->preTestsAreFinished();

        $this->assertEquals($expected, $actualPreTestsAreFinished);
    }

    /**
     * @test
     * @testdox lastIncludedDayForGamesRegistration - Returns the day before end of registration's day
     * Retourne le jour d'avant la date de clôture
     */
    public function lastIncludedDayForGamesRegistration()
    {
        $session = Session::factory()->make([
            'date_cloture_inscriptions' => Carbon::create(2021, 3, 13)
        ]);

        $actualLastIncludedDay = $session->lastIncludedDayForGamesRegistration();

        $this->assertEquals("12/03/2021", $actualLastIncludedDay);
    }

    /**
     * @test
     * @param string $currentDate
     * @param bool $expected
     * @testdox gamesRegistrationEndsInLessThanSevenDays - Is today ($currentDate) less than 7 days before ending date (10/03/2021) ? $expected
     * Est-ce qu'aujourd'hui ($currentDate) est moins de 7j avant la date de clôture (10/03/2021) ? $expected
     * @testWith        ["02/03/2021", false]
     *                  ["03/03/2021", true]
     *                  ["06/03/2021", true]
     *                  ["10/03/2021", true]
     *                  ["11/03/2021", false]
     */
    public function gamesRegistrationEndsInLessThanSevenDays(string $currentDate, bool $expected)
    {
        $currentTestDate = Carbon::createFromFormat('d/m/Y', $currentDate);
        Carbon::setTestNow($currentTestDate);

        $endRegistrationDate = Carbon::create(2021, 3, 10);
        $session = Session::factory()->make([
            'date_cloture_inscriptions' => $endRegistrationDate
        ]);
        $actualIsInLessThanSevenDays = $session->gamesRegistrationEndsInLessThanSevenDays();

        $this->assertEquals($expected, $actualIsInLessThanSevenDays);
    }

    /**
     * @test
     * @param int $sessionId
     * @param string $expected
     * @testdox name - If session ID given is $sessionId, session name is $expected
     * Si l'ID de session fourni est $sessionId, le nom de la session est $expected
     * @testWith        [1, "2001"]
     *                  [3, "2003-2004"]
     *                  [10, "2010"]
     *                  [16, "2016-2017"]
     *                  [17, "2017-2018"]
     */
    public function name(int $sessionId, string $expected)
    {
        $session = Session::factory()->make([
            'id_session' => $sessionId
        ]);

        $actualSessionName = $session->name();

        $this->assertEquals($expected, $actualSessionName);
    }

    /**
     * @test
     * @param int $sessionId
     * @param string $expected
     * @testdox nameFromId - If session ID given is $sessionId, session name is $expected
     * Si l'ID de session fourni est $sessionId, le nom de la session est $expected
     * @testWith        [1, "2001"]
     *                  [3, "2003-2004"]
     *                  [10, "2010"]
     *                  [16, "2016-2017"]
     *                  [17, "2017-2018"]
     */
    public function nameFromId(int $sessionId, string $expected)
    {
        $actualSessionExists = Session::nameFromId($sessionId);

        $this->assertEquals($expected, $actualSessionExists);
    }
}
