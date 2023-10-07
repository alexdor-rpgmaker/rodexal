<?php

namespace Tests\Feature;

/**
 * @testdox HomeRouter
 */
class HomeRouterTest extends FeatureTestCase
{
    // Index

    /**
     * @testdox On est redirigés quand on se rend sur la page d'accueil du site
     */
    public function testAccueilDuSite()
    {
        $response = $this->get('/');

        $response->assertRedirect();
    }
}
