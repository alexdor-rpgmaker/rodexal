<?php

namespace Database\Factories;

use App\PreTest;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreTestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreTest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'game_id' => $this->faker->numberBetween(5, 25),
            'type' => 'pre-qualification',
            'questionnaire' => [
                'blockingBug' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'notAutonomous' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'notLaunchable' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'severalBugs' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'spellingMistakes' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'tooHard' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'tooShort' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'unplayableAlone' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'languageUnknown' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                // 2013
                'abusiveBugs' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'notForWindows' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'noDownloadLink' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'illegalContent' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'plagiarism' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'unusualController' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'painfulHandling' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'wrongAgeCategory' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'partialCredits' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
                'annoyingLag' => [
                    'activated' => $this->faker->boolean,
                    'explanation' => $this->faker->text,
                ],
            ],
            'final_thought' => array_rand(['ok', 'not-ok']),
            'final_thought_explanation' => $this->faker->paragraphs($this->faker->numberBetween(1, 3), true),
        ];
    }
}
