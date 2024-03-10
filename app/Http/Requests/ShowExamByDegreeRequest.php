<?php

namespace App\Http\Requests;

use App\Http\Resources\ErrorsResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowExamByDegreeRequest extends FormRequest
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
            'degree' => 'required|string|in:master,graduation|',
            'college_uuid' => 'required|string|exists:colleges,uuid|',
            'specialty_uuid' => 'required|string|exists:specialties,uuid|',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null,null, false, ErrorsResource::make($validator->errors()), 422));
    }
}
