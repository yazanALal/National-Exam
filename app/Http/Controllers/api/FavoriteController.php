<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFavoriteRequest;
use App\Http\Requests\FavoriteRequest;
use App\Http\Requests\ShowFromCollegeRequest;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\QuestionResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Favorite;
use Illuminate\Support\Str;
use Exception;

class FavoriteController extends Controller
{
    use GeneralTrait;
    use UserTrait;

    public function showFavoriteQuestions(ShowFromCollegeRequest $request)
    {
        try {
            $favorites = Favorite::where('user_id', $request->user('sanctum')->id)->get();
            return $this->apiResponse(FavoriteResource::collection($favorites), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function addFavorite(AddFavoriteRequest $request)
    {
        try {

            $user = $request->user('sanctum')->id;

            $examQuestionId = $request->question;

            $add = Favorite::create([
                'user_id' => $user,
                'exam_question_id' => $examQuestionId,
                'question_id' => 1,
                'uuid' => Str::uuid(),
            ]);

            if ($add) {
                return $this->apiResponse("تمت الاضافة بنجاح", $this->getUserCollegeUuid($request));
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function displayFavoriteQuestion(FavoriteRequest $request)
    {
        try {
            $question = Favorite::whereUuid($request->uuid)->first()->examQuestion;

            return $this->apiResponse(QuestionResource::make($question), $this->getUserCollegeUuid($request));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function deleteFavoriteQuestion(FavoriteRequest $request)
    {
        try {
            $delete = Favorite::whereUuid($request->uuid)->delete();
            if ($delete > 0) {
                return $this->apiResponse("تم الحذف بنجاح", $this->getUserCollegeUuid($request));
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
