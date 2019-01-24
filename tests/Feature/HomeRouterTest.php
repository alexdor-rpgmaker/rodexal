<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\WithoutMiddleware;
/**
 * @testdox HomeRouter
 */
class HomeRouterTest extends TestCase
{
    // Index

    /**
     * @testdox On peut accéder à la page d'accueil du site
     */
    public function testAccueilDuSite()
    {
        $response = $this->get('/');

        $response->assertOk();
    }

}
