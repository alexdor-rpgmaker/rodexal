<?php

namespace Tests\Unit\Former;

use Tests\TestCase;

use App\Former\Session;

/**
 * @testdox Session
 */
class SessionTest extends TestCase
{
    /**
     * @test
     * @param int $sessionId
     * @param bool $expected
     * @testdox nameFromId - If session ID given is $sessionId, session name is $expected
     * Si l'ID de session fourni est $sessionId, le nom de la session est $expected
     * @testWith        [1, "2001"]
     *                  [3, "2003-2004"]
     *                  [10, "2010"]
     *                  [16, "2016-2017"]
     *                  [17, "2017-2018"]
     */
    public function nameFromId($sessionId, $expected)
    {
        $actualSessionExists = Session::nameFromId($sessionId);

        $this->assertEquals($expected, $actualSessionExists);
    }

    /**
     * @test
     * @param int $sessionId
     * @param bool $includeAbandoned
     * @param bool $expected
     * @testdox sessionIdExists - If session ID given is $sessionId (with abandoned sessions is $includeAbandoned), session exists is $expected
     * Si l'ID de session fourni est $sessionId (en incluant les abandonnées à $includeAbandoned), l'existence de la session est $expected
     * @testWith        [1, true, true]
     *                  [4, true, false]
     *                  [8, true, true]
     *                  [8, false, false]
     *                  [18, true, false]
     *                  [20, true, true]
     *                  [21, true, false]
     */
    public function sessionIdExists($sessionId, $includeAbandoned, $expected)
    {
        $options = ['include_abandoned_sessions' => $includeAbandoned];
        $actualSessionExists = Session::sessionIdExists($sessionId, $options);

        $this->assertEquals($expected, $actualSessionExists);
    }
}
