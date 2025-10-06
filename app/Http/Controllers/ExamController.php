<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use Illuminate\Support\Facades\DB;


class ExamController extends Controller
{
    function index()
    {
        $exams = Exam::with(['questions.options', 'course'])->get();



        return response()->json($exams);
    }

    function show($course_id)
    {

        $exams = Exam::where('course_id', $course_id)->with('questions.options')->get();

        return response()->json($exams);

    }

    function store(Request $request)
    {
        // Validate the exam data
        $validatedData = $request->validate([
                'course' => 'required|array',
            'exam_type' => 'required|in:written,oral',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_final' => 'boolean',
            'duration' => 'required_if:exam_type,written|nullable|integer|min:1',
            'instructions' => 'required_if:exam_type,oral|nullable|string',
            'questions' => 'required_if:exam_type,written|array',
            'questions.*.type' => 'required_if:exam_type,written|in:multiple_choice,short_answer,text',
            'questions.*.question' => 'required_if:exam_type,written|string',
            'questions.*.correct_answer' => 'nullable|string',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
        ]);

        try {
            // Begin transaction to ensure data integrity
            DB::beginTransaction();

            // Create the exam
            $exam = Exam::create([
                'course_id' => $validatedData['course']["id"],
                'exam_type' => $validatedData['exam_type'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'is_final' => $validatedData['is_final'] ?? false,
                'duration' => $validatedData['duration'] ?? null,
                'instructions' => $validatedData['instructions'] ?? null,
            ]);

            // If it's a written exam, create questions and options
            if ($validatedData['exam_type'] === 'written' && !empty($validatedData['questions'])) {
                foreach ($validatedData['questions'] as $questionData) {
                    $question = ExamQuestion::create([
                        'exam_id' => $exam->id,
                        'type' => $questionData['type'],
                        'question' => $questionData['question'],
                        'correct_answer' => $questionData['correct_answer'] ?? null,
                    ]);

                    // If it's a multiple choice question, create the options
                    if ($questionData['type'] === 'multiple_choice' && !empty($questionData['options'])) {
                        foreach ($questionData['options'] as $option) {
                            ExamQuestionOption::create([
                                'exam_question_id' => $question->id,
                                'option_text' => $option["option_text"],
                            ]);
                        }
                    }
                }
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'تم إنشاء الاختبار بنجاح',
                'exam' => $exam->load('questions.options'),
            ], 201);
        } catch (\Exception $e) {
            // Roll back the transaction if something goes wrong
            DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'فشل في إنشاء الاختبار',
                'error' => $e->getMessage(),
            ], 500);
        }


    }



    function destroy($id)
    {
        $exam = Exam::find($id);

        if ($exam["status"] !== "dated") {
            $exam->delete();
            return response()->json("Deleted");

        }

        return response()->json("Not Deleted");


    }
}
