<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionAnswer;
use App\Models\Exam;
use App\Models\User;


use Illuminate\Support\Facades\DB;


class ExamSubmissionController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'exam_id' => 'required|exists:exams,id',
        'answers' => 'required|array|min:1',
        'answers.*.question_id' => 'required|exists:exam_questions,id',
        'answers.*.answer' => 'required'
    ]);

    DB::beginTransaction();

    try {
        // Create exam submission record
        $submission = ExamSubmission::create([
            'user_id' => auth()->id(),
            'exam_id' => $validated['exam_id'],
        ]);

        // Loop through each answer and save individually
        foreach ($validated['answers'] as $answerData) {
            $questionId = $answerData['question_id'];
            $answer = $answerData['answer'];

            // Determine if it's MCQ (option_id) or free text
            $isMCQ = is_numeric($answer);

            ExamSubmissionAnswer::create([
                'exam_submission_id' => $submission->id,
                'question_id' => $questionId,
                'option_id' => $isMCQ ? $answer : null,
                'answer_text' => !$isMCQ ? $answer : null,
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Exam submitted successfully',
            'submission_id' => $submission->id
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to submit exam',
            'error' => $e->getMessage()
        ], 500);
    }
}
    function show($exam_id)
    {

        $exams = ExamSubmission::where('exam_id', $exam_id)->with( 'examSubmissionAnswers.question.options',"examSubmissionAnswers.option","exam","student")->get();

        return response()->json($exams);

    }

function update(Request $request, $submission_id)
{
    $submission = ExamSubmission::findOrFail($submission_id);
    $submission->teacher_comment = $request->teacher_comment;
    $submission->score = $request->score;
    $submission->save();

    $exam = $submission->exam;
    $student = $submission->student; // assuming a proper `student()` relationship

    if ($exam->is_final && $request->next_level_decision === 'pass') {
        // Get the current active enrollment
        $enrollment = $student->enrollments()->where('graduated', 0)->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Active enrollment not found'], 404);
        }

        // Get the next level
        $currentLevel = $enrollment->level; // assuming a level() relationship
        $path = $enrollment->path; // assuming a path() relationship

        $nextLevel = $path->levels()->where('order', $currentLevel->order + 1)->first();

        if ($nextLevel) {
            // Promote to next level
            $enrollment->level_id = $nextLevel->id;
        } else {
            // No next level → graduation
            $enrollment->graduated = 1;
        }

        $enrollment->save();
    }

    return response()->json(['message' => 'Correction updated successfully']);
}



}
