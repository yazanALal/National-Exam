<?php

namespace App\Http\Requests;

use App\Http\Resources\ErrorsResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CalculateExamMarkRequest extends FormRequest
{
   use GeneralTrait;
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.question_text' => 'required|string',
            'data.*.answers' => 'required|array',
            'data.*.answers.*.answer_text' => 'required|string',
            'data.*.answers.*.status' => 'required|boolean',
            'data.*.answers.*.choose' => 'required|boolean',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, null, false, ErrorsResource::make($validator->errors()), 422));
    }
}
