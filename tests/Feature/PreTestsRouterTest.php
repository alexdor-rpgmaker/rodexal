<?php

namespace Tests\Feature;

use App\User;
use App\PreTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
/**
 * @testdox PreTestsRouter
 */
class PreTestsRouterTest extends TestCase
{
    use RefreshDatabase;

    // Index

    /**
     * @testdox On peut accéder à la liste des QCMs
     */
    public function testListeDesQcm()
    {
        $response = $this->get('/qcm');

        $response->assertOk();
    }

    // Create

    /**
     * @testdox On ne peut pas accéder au formulaire d'ajout de QCM si on n'est pas juré
     */
    public function testFormulaireNouveauQcmSiNonJury()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->get('/qcm/creer');

        $response->assertForbidden();
    }

    /**
     * @testdox On peut pas accéder au formulaire d'ajout de QCM si on est pas juré
     */
    public function testFormulaireNouveauQcmSiJury()
    {
        $user = factory(User::class)->states('jury')->create();

        $response = $this->actingAs($user)
                         ->get('/qcm/creer');

        $response->assertOk();
    }

    // Store

    /**
     * @testdox On ne peut pas valider un QCM si on n'est pas jury
     */
    public function testNouveauQcmSiNonJury()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->post('/qcm');

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour ajouter un QCM, si on est jury
     */
    public function testRedirectionSiChampsManquantsSiJury()
    {
        $user = factory(User::class)->states('jury')->create();

        $response = $this->actingAs($user)
                         ->post('/qcm', [
                             'user_id' => 2,
                             'game_id' => 8
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('pre_tests', [
            'user_id' => 2,
            'game_id' => 8
        ]);
    }

    /**
     * @testdox On peut créer un QCM si on est jury
     */
    public function testNouveauQcmSiJury()
    {
        $user = factory(User::class)->states('jury')->create();
        $unsavedPreTest = factory(PreTest::class)->make();
        $response = $this->actingAs($user)
                         ->post('/qcm', [
            'gameId' => $unsavedPreTest->game_id,
            'finalThought' => true,
            'finalThoughtExplanation' => null,
            'questionnaire' => $unsavedPreTest->questionnaire
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'game_id' => $unsavedPreTest->game_id,
            'final_thought' => true,
            'final_thought_explanation' => null
        ]);
    }

    // // Edit

    // /**
    //  * @testdox On ne peut pas éditer un mot du dictionnaire si on n'est pas admin
    //  */
    // public function testModifierMotDuDictionnaireSiNonAdmin()
    // {
    //     $user = factory(User::class)->create();
    //     $preTest = factory(PreTest::class)->create();

    //     $response = $this->actingAs($user)
    //                      ->get("/qcm/{$preTest->slug}/editer");

    //     $response->assertForbidden();
    // }

    // /**
    //  * @testdox On peut éditer un mot du dictionnaire si on est admin
    //  */
    // public function testModifierMotDuDictionnaireSiAdmin()
    // {
    //     $user = factory(User::class)->states('admin')->create();
    //     $preTest = factory(PreTest::class)->create();

    //     $response = $this->actingAs($user)
    //                      ->get("/qcm/{$preTest->slug}/editer");

    //     $response->assertOk();
    // }

    // // Update

    // /**
    //  * @testdox On ne peut pas mettre à jour un mot dans le dictionnaire si on n'est pas admin
    //  */
    // public function testModificationMotDuDictionnaireSiNonAdmin()
    // {
    //     $user = factory(User::class)->create();
    //     $preTest = factory(PreTest::class)->create();

    //     $response = $this->actingAs($user)
    //                      ->put("/qcm/{$preTest->slug}");

    //     $response->assertForbidden();
    // }

    // /**
    //  * @testdox On est redirigés quand il manque des paramètres pour mettre un mot à jour, si on est admin
    //  */
    // public function testRedirectionSiChampsManquantsModificationSiAdmin()
    // {
    //     $user = factory(User::class)->states('admin')->create();
    //     $preTest = factory(PreTest::class)->create();
    //     $unsavedPreTest = factory(PreTest::class)->make();

    //     $response = $this->actingAs($user)
    //                      ->put("/qcm/{$preTest->slug}", [
    //                         'label' => $unsavedPreTest->label
    //                      ]);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('preTests', [
    //         'label' => $preTest->label
    //     ]);
    //     $this->assertDatabaseMissing('preTests', [
    //         'label' => $unsavedPreTest->label
    //     ]);
    // }

    // /**
    //  * @testdox On peut mettre à jour un mot dans le dictionnaire si on est admin
    //  */
    // public function testModificationMotDuDictionnaireSiAdmin()
    // {
    //     $user = factory(User::class)->states('admin')->create();
    //     $preTest = factory(PreTest::class)->create();
    //     $newPreTest = factory(PreTest::class)->make();

    //     $response = $this->actingAs($user)
    //                      ->put("/qcm/{$preTest->slug}", [
    //                         'label' => $newPreTest->label,
    //                         'description' => $newPreTest->description
    //                      ]);

    //     $response->assertRedirect();
    //     $this->assertDatabaseHas('preTests', [
    //         'label' => $newPreTest->label,
    //         'description' => $newPreTest->description
    //     ]);
    // }

    // // Destroy

    // /**
    //  * @testdox On ne peut pas supprimer un mot dans le dictionnaire si on n'est pas admin
    //  */
    // public function testSuppressionMotDuDictionnaireSiNonAdmin()
    // {
    //     $user = factory(User::class)->create();
    //     $preTest = factory(PreTest::class)->create();

    //     $response = $this->actingAs($user)
    //                      ->delete("/qcm/{$preTest->slug}");

    //     $response->assertForbidden();
    // }

    // /**
    //  * @testdox On peut supprimer un mot du dictionnaire si on est admin
    //  */
    // public function testSuppressionMotDuDictionnaireSiAdmin()
    // {
    //     $user = factory(User::class)->states('admin')->create();
    //     $preTest = factory(PreTest::class)->create();

    //     $response = $this->actingAs($user)
    //                      ->delete("/qcm/{$preTest->slug}");

    //     $response->assertRedirect();
    //     $this->assertDatabaseMissing('preTests', [
    //         'label' => $preTest->label
    //     ]);
    // }
}
