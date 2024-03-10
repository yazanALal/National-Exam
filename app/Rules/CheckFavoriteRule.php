<?php

namespace App\Rules;

use App\Http\Traits\UserTrait;
use App\Models\ExamQuestion;
use App\Models\Favorite;
use Illuminate\Contracts\Validation\Rule;

class CheckFavoriteRule implements Rule
{
    use UserTrait;

    public function passes($attribute, $value)
    {
        $user = auth('sanctum')->user()->id;

        // Check if the provided question is already added as favorite question

        $exist = Favorite::where('exam_question_id', $value)
            ->where('user_id', $user)->exists();

        if ($exist == true) {
            return false;
        }

        return true;
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'موجود مسبقا';
    }
}
