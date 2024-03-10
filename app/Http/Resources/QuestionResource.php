<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'question'=>$this->id,
            'question_text'=>$this->question->text,
            'answers'=> AnswerResource::collection($this->answerExamQuestions),
            'favorite'=>$this->favorite,
        ];
    }
}
