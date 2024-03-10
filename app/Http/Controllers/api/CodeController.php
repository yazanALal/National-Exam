<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateCodeRequest;
use App\Http\Traits\GeneralTrait;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CodeController extends Controller
{
    use GeneralTrait;

    public function generateCode(GenerateCodeRequest $request)
    {
        try {
            $code = Str::random(6);

            // Check if the code already exists in the table
            while (Code::where('code', $code)->exists()) {
                $code = Str::random(6);
            }
            
            $data = [
                'code' => $code,
            ];

            Code::where('user_id', $request->user_id)
            ->whereCode(null)->update($data);
            return $this->apiResponse(['code'=>$code]);
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
