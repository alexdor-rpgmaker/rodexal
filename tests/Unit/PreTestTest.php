<?php

namespace Tests\Unit;

use App\PreTest;

use Tests\TestCase;

/**
 * @testdox PreTest
 */
class PreTestTest extends TestCase
{
    // explanationsCount

    /**
     * @test
     * @testdox explanationsCount - If no explanation then returns 0
     * Si pas d'explications/de remarques alors renvoie 0
     */
    public function explanationsCount_ifNoExplanation_returnsZero()
    {
        $questionnaireWithoutExplanation = self::questionnaireNoActivatedFieldsNoExplanations();
        $preTest = PreTest::factory()->make([
            'type' => 'qcm',
            'final_thought_explanation' => "",
            'questionnaire' => $questionnaireWithoutExplanation
        ]);

        $actualExplanationsCount = $preTest->explanationsCount();

        $this->assertEquals(0, $actualExplanationsCount);
    }

    /**
     * @test
     * @testdox explanationsCount - If two questionnaire explanations and a final thought explanation then returns 3
     * Si deux explications et une explication finale alors retourne 3
     */
    public function explanationsCount_ifThreeExplanations_returnThree()
    {
        $questionnaire = self::questionnaireNoActivatedFieldsNoExplanations();
        $questionnaire['blockingBug']['explanation'] = "My second explanation";
        $questionnaire['notAutonomous']['explanation'] = "My third explanation";
        $preTest = PreTest::factory()->make([
            'type' => 'qcm',
            'final_thought_explanation' => "My first explanation",
            'questionnaire' => $questionnaire
        ]);

        $actualExplanationsCount = $preTest->explanationsCount();

        $this->assertEquals(3, $actualExplanationsCount);
    }

    /**
     * @test
     * @testdox explanationsCount - If it is a pre-qualification, then it counts the questionnaire new fields
     * Si c'est une pré-qualification, alors compte les nouveaux champs du questionnaire
     */
    public function explanationsCount_ifPreQualification_thenCountsTheQuestionnaireNewFields()
    {
        $questionnaire = self::questionnaireNoActivatedFieldsNoExplanations();
        $questionnaire['notForWindows']['explanation'] = "My Windows crash because of this game";
        $questionnaire['painfulHandling']['explanation'] = "The character seems to dance";
        $preTest = PreTest::factory()->make([
            'type' => 'pre-qualification',
            'final_thought_explanation' => "This game is lame",
            'questionnaire' => $questionnaire
        ]);

        $actualExplanationsCount = $preTest->explanationsCount();

        $this->assertEquals(3, $actualExplanationsCount);
    }

    // questionnaireHasActivatedFields

    /**
     * @test
     * @testdox questionnaireHasActivatedFields - If no activated fields then returns false
     * Si pas de champ activé alors renvoie faux
     */
    public function questionnaireHasActivatedFields_ifNoActivatedFields_returnsFalse()
    {
        $questionnaireWithoutActivatedFields = self::questionnaireNoActivatedFieldsNoExplanations();
        $preTest = PreTest::factory()->make([
            'type' => 'qcm',
            'questionnaire' => $questionnaireWithoutActivatedFields
        ]);

        $actualExplanationsCount = $preTest->questionnaireHasActivatedFields();

        $this->assertEquals(false, $actualExplanationsCount);
    }

    /**
     * @test
     * @testdox questionnaireHasActivatedFields - If two activated fields then returns true
     * Si deux champs activés alors renvoie vrai
     */
    public function questionnaireHasActivatedFields_ifThreeExplanations_returnThree()
    {
        $questionnaire = self::questionnaireNoActivatedFieldsNoExplanations();
        $questionnaire['blockingBug']['activated'] = true;
        $questionnaire['notAutonomous']['activated'] = true;
        $preTest = PreTest::factory()->make([
            'type' => 'qcm',
            'questionnaire' => $questionnaire
        ]);

        $actualExplanationsCount = $preTest->questionnaireHasActivatedFields();

        $this->assertEquals(true, $actualExplanationsCount);
    }

    private static function questionnaireNoActivatedFieldsNoExplanations(): array
    {
        return [
            'blockingBug' => [
                'activated' => false,
                'explanation' => "",
            ],
            'notAutonomous' => [
                'activated' => false,
                'explanation' => "",
            ],
            'notLaunchable' => [
                'activated' => false,
                'explanation' => "",
            ],
            'severalBugs' => [
                'activated' => false,
                'explanation' => "",
            ],
            'spellingMistakes' => [
                'activated' => false,
                'explanation' => "",
            ],
            'tooHard' => [
                'activated' => false,
                'explanation' => "",
            ],
            'tooShort' => [
                'activated' => false,
                'explanation' => "",
            ],
            'unplayableAlone' => [
                'activated' => false,
                'explanation' => "",
            ],
            'languageUnknown' => [
                'activated' => false,
                'explanation' => "",
            ],
        ];
    }
}
