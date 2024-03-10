<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddIntoCollegeRequest;
use App\Http\Requests\ChooseExamByBookRequest;
use App\Http\Requests\CollegeIdCheckRequest;
use App\Http\Requests\DeleteSubjectRequest;
use App\Http\Requests\ShowFromCollegeRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectAdminResource;
use App\Http\Resources\SubjectResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Str;

class SubjectController extends Controller
{

    use GeneralTrait;
    use UserTrait;


    // retrieve subjects depends on college
    public function index(CollegeIdCheckRequest $request)
    {
        try {
            $collegeId = $request->college_id;
            $subjects = Subject::where('college_id', $collegeId)->get();
            return $this->apiResponse(SubjectAdminResource::collection($subjects));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    //retrieve subjects depends on specialty 
    public function showSubjects(ChooseExamByBookRequest $request)
    {
        try {
            $specialtyId = $this->getUserSpecialty($request);

            if (is_null($specialtyId)) {
                return $this->apiResponse(null, null, false, null, 400);
            }

            $subjects = Subject::where(function ($query) use ($specialtyId) {
                $query->where('specialty_id', $specialtyId)
                    ->orWhereNull('specialty_id');
            })->where('college_id', $this->getUserCollegeId($request))->get();
            return $this->apiResponse(SubjectResource::collection($subjects), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function addSubject(AddIntoCollegeRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'college_id' => $request->college_id,
                'uuid' => Str::uuid(),
                'specialty_id' => $request->specialty_id,
            ];

            $insert = Subject::create($data);
            if ($insert) {
                return $this->apiResponse(null, null, true, 'تمت الاضافة بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function updateSubject(UpdateSubjectRequest $request)
    {
        try {
            $id = $request->subject_id;
            $data = [
                'college_id' => $request->college_id,
                'specialty_id' => $request->specialty_id,
                'name' => $request->name
            ];

            $update = Subject::whereId($id)->update($data);

            if ($update > 0) {
                return $this->apiResponse('تم التعديل بنجاح');
            }
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
