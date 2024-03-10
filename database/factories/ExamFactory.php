<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid'=>Str::uuid(),
            'name'=>fake()->lastName(),
            'specialty_id'=>Specialty::all("id")->random(),
            'degree'=> fake()->randomElement(["master", "graduation"]),
            'type'=>fake()->randomElement(["exam", "book"])
        ];
    }
}
