<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSuggestionRequest;
use App\Http\Resources\SuggestionResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Suggestion;
use Exception;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    use GeneralTrait;
    use UserTrait;

    public function addSuggestion(Request $request)
    {
        try {
            $userId = $request->user('sanctum')->id;
            $suggestion = Suggestion::create([
                'text' => $request->text,
                'user_id' => $userId,
            ]);
            if ($suggestion) {
                return $this->apiResponse(null, $this->getUserCollegeUuid($request), true, 'تم الارسال بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {

            $suggestions = Suggestion::all();
            return $this->apiResponse(SuggestionResource::collection($suggestions));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function deleteSuggestion(DeleteSuggestionRequest $request)
    {
        try {
            $delete = Suggestion::where('id', $request->suggestion_id)->delete();
            if ($delete > 0) {
                return $this->apiResponse('تم الحذف بنجاح');
            } 
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
