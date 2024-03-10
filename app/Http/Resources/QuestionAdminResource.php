<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAdminResource extends JsonResource
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
            'exam_id'=>$this->exam_id,
            'question_id' => $this->question->id,
            'subject_id'=>$this->question->subject_id,
            'question_text' => $this->question->text,
            'question_number' => $this->question_number,
            'answers' => AnswerAdminResource::collection($this->answerExamQuestions)
        ];
    }
}
