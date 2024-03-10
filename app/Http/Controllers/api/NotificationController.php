<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendNotificationRequest;
use App\Http\Requests\ShowFromCollegeRequest;
use App\Http\Requests\StoreDeviceTokenRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Traits\FireBase;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Notification;
use App\Models\User;
use Exception;

class NotificationController extends Controller
{
    use GeneralTrait;
    use FireBase;
    use UserTrait;
    public function sendNotification(SendNotificationRequest $request)
    {
        try {
            $content = [
                'body' => $request->body,
                'title' => $request->title
            ];

            if ($request->college_uuid == "all") {
                $tokens = User::whereNotNull('device')->pluck('device');
            } else {
                $collegeId = $this->getCollegeId($request);
                $tokens = User::whereIn('id', function ($query) use ($collegeId) {
                    $query->select('user_id')
                        ->from('codes')
                        ->where('college_id', $collegeId);
                })->whereNotNull('device')->pluck('device');
            }

            $send = $this->HandelDataAndSendNotify($tokens, $content);

            if ($send) {
                $data = [
                    'body' => $request->body,
                    'title' => $request->title,
                    'college' => $request->college_uuid,
                ];
                $insert = Notification::create($data);
                return $this->apiResponse(null, null, true, 'تم الارسال بنجاح');
            } else {
                return $this->apiResponse(null, null, false, 'حدث خطأ اعد المحاولة', 400);
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function showNotifications(ShowFromCollegeRequest $request)
    {
        try {
            $userCollege = $this->getUserCollegeUuid($request);
            $notifications = Notification::whereIn('college', ['all', $userCollege])->orderBy('created_at', 'desc')->get();

            return $this->apiResponse(NotificationResource::collection($notifications), $this->getUserCollegeUuid($request));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function storeDeviceToken(StoreDeviceTokenRequest $request)
    {
        try {
            $user = $request->user('sanctum');
            $user->device = $request->device_token;
            $user->save();
            return $this->apiResponse('تم التخزين بنجاح');
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
