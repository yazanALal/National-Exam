<?php

namespace App\Http\Requests;

use App\Http\Resources\ErrorsResource;
use App\Http\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubjectRequest extends FormRequest
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
            'college_id' => 'required|integer|exists:colleges,id',
            'name' => 'required|string',
            'subject_id' => 'required|integer|exists:subjects,id',
            'specialty_id' => 'integer|exists:specialties,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {


        throw new HttpResponseException($this->apiResponse(null, null, false, ErrorsResource::make($validator->errors()), 422));
    }
}
