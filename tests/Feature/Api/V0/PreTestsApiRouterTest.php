<?php

namespace Tests\Feature\Api\V0;

use App\PreTest;
use Tests\Feature\FeatureTest;

/**
 * @testdox PreTestsApiRouter
 */
class PreTestsApiRouterTest extends FeatureTest
{
    /**
     * @testdox On peut accéder à la liste des QCM remplis via l'API
     */
    public function testQcmApiIndex()
    {
        PreTest::factory()->count(3)->create();

        $response = $this->get('/api/v0/qcm');

        $response->assertOk();
    }
}
