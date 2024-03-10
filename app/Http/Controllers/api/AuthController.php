<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogInAdminRequest;
use App\Http\Requests\LogInRequest;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Admin;
use App\Models\Code;
use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use GeneralTrait;
    use UserTrait;




    public function registerAdmin(RegisterAdminRequest $request)
    {
        try {

            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>Hash::make($request->password) ,
                'uuid' => Str::uuid(),
            ]);
            if($admin){
                return $this->apiResponse("تمت الاضافة بنجاح");
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
    public function logInAdmin(LogInAdminRequest $request)
    {

        try {
            $admin = Admin::whereEmail($request->email)->first();
            if (!$admin) {
                return $this->apiResponse(null,null, false, ["خطا في البريد او كلمة السر"], 422);
            }
            if (!Hash::check($request->password, $admin->password)) {
                return $this->apiResponse(null,null, false, ["خطا في البريد او كلمة السر"], 422);
            }

            $data["token"] = $admin->createToken('api_token')->plainTextToken;
            return $this->apiResponse($data);
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function registerUser(RegisterRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'uuid' => Str::uuid(),
            ]);

            $user->codes()->create([
                'college_id' => College::whereUuid($request->college_uuid)->first()->id,
            ]);

            return $this->apiResponse(null, null,true, 'تم التسجيل بنجاح');
        } catch (\Exception $e) {
            return $this->apiResponse(null, null,false, $e->getMessage(), 500);
        }
    }

    public function logInUser(LogInRequest $request)
    {
        
        try {
            $code = Code::whereCode($request->code)->first();
            if (!$code) {
                return $this->apiResponse(null, null,false, ["خطا في الاسم او  الكود"], 422);
            }
            if ($code->user->name != $request->name) {
                return $this->apiResponse(null, null,false, ["خطا في الاسم او  الكود"], 422);
            }

            $user = User::whereId(function ($query) use ($request) {
                $query->select('user_id')
                    ->from('codes')
                    ->where('code', $request->code);
            })->first();

            $token = $user->tokens();

            if ($token) {
                $token->delete();
            }

            $data["token"] = $user->createToken('api_token')->plainTextToken;
            $data['code'] = $request->code;
            return $this->apiResponse($data, $this->getUserCollege($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null,null, false, $e->getMessage(), 500);
        }
    }

    public function logoutUser(Request $request)
    {

        if (auth('sanctum')->user()) {
            auth('sanctum')->user()->tokens()->delete();

            return $this->apiResponse(null,null, true, 'Logout success');
        }
    }

    public function logoutAdmin(Request $request)
    {

        if (auth('sanctum')->user('admin')) {
            auth('sanctum')->user('admin')->tokens()->delete();

            return $this->apiResponse(null, null, true, 'Logout success');
        }
    }
}
