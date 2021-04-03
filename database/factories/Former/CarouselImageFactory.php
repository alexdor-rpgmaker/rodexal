<?php

namespace Database\Factories\Former;

use App\Former\CarouselImage;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarouselImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarouselImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url_image' => 'uploads/carrousel/id-'.mt_rand(1111, 3333).'.png',
            'alt' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
        ];
    }
}
