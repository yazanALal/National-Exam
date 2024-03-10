<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddExamRequest;
use App\Http\Requests\CalculateExamMarkRequest;
use App\Http\Requests\ChooseExamByBookRequest;
use App\Http\Requests\ChooseExamByExamRequest;
use App\Http\Requests\ChooseExamBySubjectRequest;
use App\Http\Requests\DeleteExamRequest;
use App\Http\Requests\ShowExamByDegreeRequest;
use App\Http\Requests\ShowFromCollegeIdRequest;
use App\Http\Resources\ExamAdminResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\QuestionResource;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\AnswerExamQuestion;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ExamController extends Controller
{
    use UserTrait;
    use GeneralTrait;
    //retrieve book questions
    public function chooseExamByBook(ChooseExamByBookRequest $request)
    {
        try {
            $specialtyId = $this->getUserSpecialty($request);
            $userId=$request->user('sanctum')->id;
            if (is_null($specialtyId)) {
                return $this->apiResponse(null, null, false, null, 400);
            }

            $questions =ExamQuestion::whereIn('exam_id', function ($query) use ($specialtyId) {
                $query->select('id')
                ->from('exams')
                ->where('specialty_id', $specialtyId)
                ->where('type', 'book');
            })
            ->leftJoin('favorites', function ($join) use ($userId) {
                $join->on('exam_questions.id', '=', 'favorites.exam_question_id')
                ->where('favorites.user_id', $userId);
            })
            ->select('exam_questions.*', DB::raw("(CASE WHEN favorites.id IS NOT NULL THEN true ELSE false END) as favorite"))
            ->inRandomOrder()
            ->take(50)
            ->get();

            return $this->apiResponse(QuestionResource::collection($questions), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function chooseExamBySubject(ChooseExamBySubjectRequest $request)
    {
        try {
            $subjectId = $this->getUserSubject($request);
            $userId = $request->user('sanctum')->id;
            
            $questions = ExamQuestion::whereIn('exam_questions.question_id', function ($query) use ($subjectId) {
                $query->select('id')
                ->from('questions')
                ->where('subject_id', $subjectId);
            })
            ->leftJoin('favorites', function ($join) use ($userId) {
                $join->on('exam_questions.id', '=', 'favorites.exam_question_id')
                ->where('favorites.user_id', $userId);
            })
            ->select('exam_questions.*', DB::raw("(CASE WHEN favorites.id IS NOT NULL THEN true ELSE false END) as favorite"))
            ->inRandomOrder()
            ->take(50)
            ->get();

            return $this->apiResponse(QuestionResource::collection($questions), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function chooseExamByExam(ChooseExamByExamRequest $request)
    {
        try {

            $examId = $this->getUserExam($request);
            $userId = $request->user('sanctum')->id;
            $questions = ExamQuestion::where('exam_id', $examId)
            ->leftJoin('favorites', function ($join) use ($userId) {
                $join->on('exam_questions.id', '=', 'favorites.exam_question_id')
                ->where('favorites.user_id', $userId);
            })
            ->select('exam_questions.*', DB::raw("(CASE WHEN favorites.id IS NOT NULL THEN true ELSE false END) as favorite"))
            ->orderBy('question_number', 'asc')
            ->take(50)
            ->get();

            return $this->apiResponse(QuestionResource::collection($questions), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function showExamByDegree(ShowExamByDegreeRequest $request)
    {
        try {
            $specialtyId = $this->getUserSpecialty($request);

            if (is_null($specialtyId)) {
                return $this->apiResponse(null, null, false, null, 400);
            }

            $exams = Exam::whereDegree($request->degree)
                ->where('specialty_id', $specialtyId)
                ->where('type', 'exam')
                ->get();
            return $this->apiResponse(ExamResource::collection($exams), $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }



    public function calculateExamMark(CalculateExamMarkRequest $request)
    {
        try {
            $data = $request->data;
            $wrongAnswers = [];
            $marks = 0;
            $result = [];

            foreach ($data as $question) {
                foreach ($question['answers'] as $answer) {
                    if ($answer['choose'] && $answer['status']) {
                        $marks += 2;
                    } elseif ($answer['choose'] && $answer['status'] == false) {
                        $wrongAnswers[] = $question;
                        break;
                    }
                }
            }

            $result['questions'] = $wrongAnswers;
            $result['mark'] = $marks;
            return $this->apiResponse($result, $this->getUserCollegeUuid($request));
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    /// Admin functions 


    public function addExam(AddExamRequest $request)
    {
        try {
            $data = [
                'type' => $request->type,
                'specialty_id' => $request->specialty_id,
                'name' => $request->name,
                'degree' => $request->degree,
                'uuid'=>Str::uuid(),
            ];

            $insert = Exam::create($data);
            if ($insert) {
                return $this->apiResponse("تمت الاضافة بنجاح");
            }
        } catch (\Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }


    public function showExamByCollege(ShowFromCollegeIdRequest $request)
    {
        try {
            $exams = Exam::whereIn('specialty_id', function ($query) use ($request) {
                $query->select('id')
                    ->from('specialties')
                    ->where('college_id', $request->college_id);
            })->get();
            return $this->apiResponse(ExamAdminResource::collection($exams));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    
}
