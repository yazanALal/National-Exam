<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\College;

class ValidCollegeUUID implements Rule
{
    public function passes($attribute, $value)
    {
        if ($value === 'all') {
            return true;
        }

        return College::where('uuid', $value)->exists();
    }

    public function message()
    {
        return 'خطأ في بيانات الكلية';
    }
}
