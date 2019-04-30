<?php

namespace Tests\Feature;

use App\User;
use App\Word;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @testdox WordsRouter
 */
class WordsRouterTest extends TestCase
{
    use RefreshDatabase;

    // Index

    /**
     * @testdox On peut accéder à la page d'accueil du dictionnaire
     */
    public function testRacineDuDictionnaire()
    {
        $response = $this->get('/dictionnaire');

        $response->assertOk();
    }

    // Create

    /**
     * @testdox On ne peut pas accéder au formulaire d'ajout si on n'est pas admin
     */
    public function testFormulaireNouveauMotDuDictionnaireSiNonAdmin()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->get('/dictionnaire/creer');

        $response->assertForbidden();
    }

    /**
     * @testdox On peut accéder au formulaire d'ajout si on est admin
     */
    public function testFormulaireNouveauMotDuDictionnaireSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
            ->get('/dictionnaire/creer');

        $response->assertOk();
    }

    // Store

    /**
     * @testdox On ne peut pas créer un mot dans le dictionnaire si on n'est pas admin
     */
    public function testNouveauMotDuDictionnaireSiNonAdmin()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->post('/dictionnaire');

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour ajouter un mot, si on est admin
     */
    public function testRedirectionSiChampsManquantsSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $unsavedWord = factory(Word::class)->make();

        $response = $this->actingAs($user)
            ->post('/dictionnaire', [
                'label' => $unsavedWord->label
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('words', [
            'label' => $unsavedWord->label
        ]);
    }

    /**
     * @testdox On peut créer un mot dans le dictionnaire si on est admin
     */
    public function testNouveauMotDuDictionnaireSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $unsavedWord = factory(Word::class)->make();

        $response = $this->actingAs($user)
            ->post('/dictionnaire', [
                'label' => $unsavedWord->label,
                'description' => $unsavedWord->description
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('words', [
            'label' => $unsavedWord->label,
            'description' => $unsavedWord->description
        ]);
    }

    // Edit

    /**
     * @testdox On ne peut pas éditer un mot du dictionnaire si on n'est pas admin
     */
    public function testModifierMotDuDictionnaireSiNonAdmin()
    {
        $user = factory(User::class)->create();
        $word = factory(Word::class)->create();

        $response = $this->actingAs($user)
            ->get("/dictionnaire/{$word->slug}/editer");

        $response->assertForbidden();
    }

    /**
     * @testdox On peut éditer un mot du dictionnaire si on est admin
     */
    public function testModifierMotDuDictionnaireSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $word = factory(Word::class)->create();

        $response = $this->actingAs($user)
            ->get("/dictionnaire/{$word->slug}/editer");

        $response->assertOk();
    }

    // Update

    /**
     * @testdox On ne peut pas mettre à jour un mot dans le dictionnaire si on n'est pas admin
     */
    public function testModificationMotDuDictionnaireSiNonAdmin()
    {
        $user = factory(User::class)->create();
        $word = factory(Word::class)->create();

        $response = $this->actingAs($user)
            ->put("/dictionnaire/{$word->slug}");

        $response->assertForbidden();
    }

    /**
     * @testdox On est redirigés quand il manque des paramètres pour mettre un mot à jour, si on est admin
     */
    public function testRedirectionSiChampsManquantsModificationSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $word = factory(Word::class)->create();
        $unsavedWord = factory(Word::class)->make();

        $response = $this->actingAs($user)
            ->put("/dictionnaire/{$word->slug}", [
                'label' => $unsavedWord->label
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('words', [
            'label' => $word->label
        ]);
        $this->assertDatabaseMissing('words', [
            'label' => $unsavedWord->label
        ]);
    }

    /**
     * @testdox On peut mettre à jour un mot dans le dictionnaire si on est admin
     */
    public function testModificationMotDuDictionnaireSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $word = factory(Word::class)->create();
        $newWord = factory(Word::class)->make();

        $response = $this->actingAs($user)
            ->put("/dictionnaire/{$word->slug}", [
                'label' => $newWord->label,
                'description' => $newWord->description
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('words', [
            'label' => $newWord->label,
            'description' => $newWord->description
        ]);
    }

    // Destroy

    /**
     * @testdox On ne peut pas supprimer un mot dans le dictionnaire si on n'est pas admin
     */
    public function testSuppressionMotDuDictionnaireSiNonAdmin()
    {
        $user = factory(User::class)->create();
        $word = factory(Word::class)->create();

        $response = $this->actingAs($user)
            ->delete("/dictionnaire/{$word->slug}");

        $response->assertForbidden();
    }

    /**
     * @testdox On peut supprimer un mot du dictionnaire si on est admin
     */
    public function testSuppressionMotDuDictionnaireSiAdmin()
    {
        $user = factory(User::class)->states('admin')->create();
        $word = factory(Word::class)->create();

        $response = $this->actingAs($user)
            ->delete("/dictionnaire/{$word->slug}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('words', [
            'label' => $word->label
        ]);
    }
}
