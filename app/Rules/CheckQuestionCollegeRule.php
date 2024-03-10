<?php

namespace App\Rules;

use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\ExamQuestion;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class CheckQuestionCollegeRule implements Rule
{

    use UserTrait;
    use GeneralTrait;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    // Check if the question belongs to the same user college
    public function passes($attribute, $value)
    {

        $questionCollege = ExamQuestion::where('id', $value)->first()->exam->specialty->college->id;

        if ($this->getUserCollegeId($this->request) == $questionCollege) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'بيانات غير صالحة';
    }
}
