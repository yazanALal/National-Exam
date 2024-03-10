<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnswerExamQuestion>
 */
class AnswerExamQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'exam_question_id' => ExamQuestion::all('id')->random(),
            'answer_id' => Answer::all('id')->random(),
            'status' => fake()->boolean(20)
        ];
    }
}
