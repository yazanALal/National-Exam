<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PhoneRule implements Rule
{


    public function passes($attribute, $value)
    {
        $user = auth('sanctum')->user();

        // Check if the provided phone number matches the phone number of the user making the request
        if ($user->phone === $value) {
            return true;
        }
        $exist = User::where('phone', $value)->exists();

        return !$exist;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'الرقم موجود مسبقا';
    }
}
