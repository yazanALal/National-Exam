<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'=>fake()->title(),
            'description'=>fake()->text(30),
            'url'=>json_encode([
                "url1" => fake()->imageUrl(),
                "url2" => fake()->imageUrl(),
                "url3" => fake()->imageUrl(),
            ]),
        ];
    }
}
