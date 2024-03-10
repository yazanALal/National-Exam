<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCollegeRequest;
use App\Http\Requests\ShowFromCollegeRequest;
use App\Http\Resources\CollegeAdminResource;
use App\Http\Resources\CollegeResource;
use App\Http\Resources\SpecialtyResource;
use App\Http\Traits\FileUploader;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollegeController extends Controller
{
    use GeneralTrait;
    use UserTrait;
    use FileUploader;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $colleges = College::all();
            return $this->apiResponse(CollegeResource::collection($colleges));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function indexForAdmin()
    {
        try {
            $colleges = College::all();
            return $this->apiResponse(CollegeAdminResource::collection($colleges));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    // show specialties for on college
    public function showSpecialties(ShowFromCollegeRequest $request)
    {
        try {

            $specialties = College::whereUuid($request->college_uuid)->first()->specialties;
            return $this->apiResponse(SpecialtyResource::collection($specialties), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function addCollege(AddCollegeRequest $request)
    {
        $newImage = $this->uploadImage($request, 'college');
        if ($newImage) {
            $data = [
                'image' => $newImage,
                'name' => $request->name,
                'type' => $request->type,
                'uuid' => Str::uuid(),
            ];
           College::create($data);
           return $this->apiResponse("تمت الاضافة بنجاح");
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
