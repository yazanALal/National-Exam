<?php

namespace App\Http\Requests;

use App\Http\Resources\ErrorsResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateQuestionRequest extends FormRequest
{
    use GeneralTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'exam_id' => 'required|integer|exists:exams,id',
            'question_text' => 'required|string',
            'question_id' => 'required|integer|exists:questions,id',
            'question_number' => 'required|integer',
            'subject_id' => 'required|integer|exists:exams,id',
            'answers' => 'required|array|size:5',
            'answers.*.answer_text' => 'required|string',
            'answers.*.status' => 'required|boolean',
            'answers.*.answer_id' => 'required|integer|exists:answers,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, null, false, ErrorsResource::make($validator->errors()), 422));
    }
}
