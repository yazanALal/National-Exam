<?php

namespace App\Http\Traits;

use App\Models\Code;
use App\Models\College;
use App\Models\Exam;
use App\Models\Specialty;
use App\Models\Subject;
use App\Models\User;
use Exception;

trait UserTrait
{

    //get user college uuid from header
    public function getUserCollegeUuid($request)
    {
        try {
            $college_uuid = Code::whereCode((string)($request->header('code')))->first()->college->uuid;
            return $college_uuid;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
    // get college id from code
    public function getUserCollegeId($request)
    {
        try {
            $college_id = Code::whereCode((string)($request->header('code')))->first()->college->id;
            return $college_id;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    //get user college uuid from body for login method
    public function getUserCollege($request)
    {
        try {
            $college_uuid = Code::whereCode($request->code)->first()->college->uuid;
            return $college_uuid;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function getUserSpecialty($request)
    {
        try {
            $specialtyId = Specialty::whereUuid($request->specialty_uuid)
                ->where('college_id', $this->getUserCollegeId($request))->first()->id;
            return $specialtyId;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function getUserExam($request)
    {
        try {
            $examId = Exam::whereUuid($request->exam_uuid)->first()->id;
            return $examId;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function getUserSubject($request)
    {
        try {
            $subjectId = Subject::whereUuid($request->subject_uuid)->first()->id;
            return $subjectId;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
    // get college id from uuid
    public function getCollegeId($request)
    {
        try {
            $collegeId = College::where('uuid', $request->college_uuid)->first->id;
            return $collegeId;
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
