<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSliderRequest;
use App\Http\Requests\DeleteSliderRequest;
use App\Http\Requests\ShowSliderRequest;
use App\Http\Resources\SliderResource;
use App\Http\Traits\FileUploader;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Slider;
use Exception;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use GeneralTrait;
    use UserTrait;
    use FileUploader;
    public function index(Request $request)
    {
        try {
            $slider = Slider::all();
            return $this->apiResponse(SliderResource::collection($slider));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function addSlider(AddSliderRequest $request)
    {
        try {
            $images = $this->uploadMultiImage($request);
            if ($images) {
                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'url' => $images,
                    'position' => $request->position
                ];
                $slider = Slider::create($data);
                return $this->apiResponse('تمت الاضافة بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
    // retrieve  slider
    public function showSlider(ShowSliderRequest $request)
    {
        try {
            $sliders = Slider::where('position', $request->position)->get();
            return $this->apiResponse(SliderResource::collection($sliders));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function deleteSlider(DeleteSliderRequest $request)
    {
        try {
            $id = $request->id;

            $delete = Slider::whereId($id)->first();

            $imageUrls = $delete->url;

            if (is_array($imageUrls)) {
                foreach ($imageUrls as $imageUrl) {
                    $url = $imageUrl['url'];
                    $filePath = public_path($url);

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $delete = Slider::whereId($id)->delete();
            if ($delete > 0) {
                return $this->apiResponse('تم الحذف بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
