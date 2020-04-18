<?php

namespace Tests\Feature;

/**
 * @testdox MusicsRouter
 */
class MusicsRouterTest extends FeatureTest
{
    // Index

    /**
     * @testdox On peut accéder au jukebox
     */
    public function testJukebox()
    {
        $response = $this->get('/jukebox');

        $response->assertOk();
    }
}
