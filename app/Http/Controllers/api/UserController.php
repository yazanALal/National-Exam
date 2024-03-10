<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserAdminResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\FileUploader;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Code;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GeneralTrait;
    use UserTrait;
    use FileUploader;

    public function showProfile(Request $request)
    {
        try {
            $user = $request->user('sanctum');
            if ($user) {
                return $this->apiResponse(UserResource::make($user), $this->getUserCollegeUuid($request));
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $userUuid = $request->user('sanctum')->uuid;
            $user = User::whereUuid($userUuid)->update([
                'phone' => $request->phone,
                'name' => $request->name,
            ]);
            if ($user) {
                return $this->apiResponse('تم التعديل بنجاح', $this->getUserCollegeUuid($request), true);
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    // Admin function 

    public function showUsersForGeneratingCode()
    {
        try {
            $users = Code::whereCode(null)->get();
            return $this->apiResponse(UserAdminResource::collection($users));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function updateProfileImage(UpdateProfileImageRequest $request)
    {
        try {
            $oldImage = $request->user('sanctum')->image;

            if ($oldImage) {

                $imagePath = public_path($oldImage);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $newImage = $this->uploadImage($request, 'profile');
            if ($newImage) {
                $data = [
                    'image' => $newImage,
                ];
                User::whereId($request->user('sanctum')->id)->update(['image' => $newImage]);
            }
            return $this->apiResponse('تمت اضافة الصورة بنجاح', $this->getUserCollegeUuid($request));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
