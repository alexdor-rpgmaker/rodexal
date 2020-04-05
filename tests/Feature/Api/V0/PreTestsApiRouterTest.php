<?php

namespace Tests\Feature;

use App\PreTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @testdox PreTestsApiRouter
 */
class PreTestsApiRouterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @testdox On peut accéder à la liste des QCM remplis via l'API
     */
    public function testQcmApiIndex()
    {
        factory(PreTest::class, 3)->create();

        $response = $this->get('/api/v0/qcm');

        $response->assertOk();
    }
}
