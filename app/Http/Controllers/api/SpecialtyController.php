<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddIntoCollegeRequest;
use App\Http\Requests\ShowFromCollegeIdRequest;
use App\Http\Requests\ShowFromCollegeRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SpecialtyAdminResource;
use App\Http\Traits\GeneralTrait;
use App\Models\College;
use App\Models\Specialty;
use Exception;
use Illuminate\Support\Str;
class SpecialtyController extends Controller
{
    use GeneralTrait;
    public function addSpecialty(AddIntoCollegeRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'college_id' => $request->college_id,
                'uuid' => Str::uuid(),
            ];

            $insert = Specialty::create($data);
            if ($insert) {
                return $this->apiResponse(null, null, true, 'تمت الاضافة بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function updateSpecialty(UpdateSpecialtyRequest $request)
    {
        try {
            $id = $request->specialty_id;
            $data = [
                'college_id' => $request->college_id,
                'name' => $request->name
            ];

            $update = Specialty::whereId($id)->update($data);

            if ($update > 0) {
                return $this->apiResponse('تم التعديل بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
// for admin
    public function showSpecialties(ShowFromCollegeIdRequest $request)
    {
        try {

            $specialties = Specialty::where('college_id',$request->college_id)->get();
            return $this->apiResponse(SpecialtyAdminResource::collection($specialties));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
