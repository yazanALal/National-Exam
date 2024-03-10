<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddQuestionRequest;
use App\Http\Requests\ShowQuestionsByExamRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionAdminResource;
use App\Http\Traits\GeneralTrait;
use App\Models\ExamQuestion;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionController extends Controller
{

    use GeneralTrait;
    // Admin functions
    public function showQuestionsByExam(ShowQuestionsByExamRequest $request)
    {
        try {
            $questions = ExamQuestion::where('exam_id', $request->exam_id)
                ->orderBy('question_number', 'asc')->get();
            return $this->apiResponse(QuestionAdminResource::collection($questions));
        } catch (Exception $e) {
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function addQuestionAndAnswers(AddQuestionRequest $request)
    {

        DB::beginTransaction();
        try {

            $questionData = [
                'text' => $request->question_text,
                'uuid' => Str::uuid(),
                'subject_id' => $request->subject_id,
            ];

            DB::table('questions')->insert($questionData);

            $examQuestionData = [
                'question_id' => DB::getPdo()->lastInsertId(),
                'exam_id' => $request->exam_id,
                'question_number' => $request->question_number,
            ];
            DB::table('exam_questions')->insert($examQuestionData);

            $answersData = [];
            foreach ($request->answers as $answer) {
                $answersData[] = [
                    'uuid' => Str::uuid(),
                    'text' => $answer['answer_text'],
                ];
            }

            DB::table('answers')->insert($answersData);

            $answerExamData = [];
            $examQuestionId = DB::table('exam_questions')
                ->orderBy('id', 'desc')
                ->take(1)
                ->first()->id;

            $answerIds = DB::table('answers')
                ->orderBy('id', 'desc')
                ->take(count($request->answers))
                ->pluck('id')->toArray();

            $answerIds = array_reverse($answerIds);

            for ($i = 0; $i < count($answerIds); $i++) {
                $answerExamData[] = [
                    'answer_id' => $answerIds[$i],
                    'exam_question_id' => $examQuestionId,
                    'status' => $request->answers[$i]['status'],
                ];
            }

            DB::table('answer_exam_questions')->insert($answerExamData);

            DB::commit();
            return $this->apiResponse('تمت الاضافة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }

    public function update(UpdateQuestionRequest $request)
    {
        DB::beginTransaction();
        try {

            $questionId = $request->question_id;
            $questionData = [
                'text' => $request->question_text,
                'subject_id' => $request->subject_id,
            ];

            DB::table('questions')
                ->where('id', $questionId)
                ->update($questionData);

            $examQuestion = DB::table('exam_questions')
                ->where('exam_id', $request->exam_id)
                ->where('question_id', $request->question_id)
                ->first();

            if ($examQuestion) {
                $examQuestionId = $examQuestion->id;
            }

            $examQuestionData = [
                'question_number' => $request->question_number,
            ];

            DB::table('exam_questions')->where('id', $examQuestionId)
                ->update($examQuestionData);

            $answersData = [];
            foreach ($request->answers as $answer) {
                $answersData['text'] = $answer['answer_text'];
                DB::table('answers')->where('id', $answer['answer_id'])->update($answersData);

                $answersStatus['status'] = $answer['status'];
                DB::table('answer_exam_questions')->where('answer_id', $answer['answer_id'])
                    ->where("exam_question_id", $examQuestionId)->update($answersStatus);
            }

            DB::commit();
            return $this->apiResponse('تمت التعديل بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, null, false, $e->getMessage(), 500);
        }
    }
}
