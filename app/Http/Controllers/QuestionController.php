<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;

use Carbon\Carbon;

class QuestionController extends Controller
{


    public function store(Request $request)
    {

        // Authorize only admin or teacher roles
        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request["question_type"] == "text") {
            $question = new Question();
            $question->question_text = $request["question_text"];
            $question->due_date = Carbon::parse($request["due_date"])->format('Y-m-d H:i:s');

            $question->instructions = $request["instructions"];
            $question->question_type = "text";
            $question->course_id = $request->course["id"];

            if ($request['attachment']) {

                $file = $request['attachment'];
                if (!is_dir(storage_path('app/public/questions-files'))) {
                    mkdir(storage_path('app/public/questions-files'), 0755, true);
                }
                $path = $file->store('questions-files', 'public');
                $question->attachment = $path;


            }
            $question->save();


        }
        return response()->json([
            'message' => 'classwork created successfully!',
        ]);
    }

    public function show($course_id)
    {

        // $user = User::find(5);

        // $currentEnrollment = $user->enrollments->where("graduated", 0)->first();

        if (auth()->user()->hasRole(['admin', 'teacher'])) {
            $questions = Question::where('course_id', $course_id)->with('answers.student')->get();
            return response()->json($questions);

        } else {

            $questions = Question::where('course_id', $course_id)->get();
            return response()->json($questions);

        }


    }

    public function destroy($id)
    {

        if (!auth()->user()->hasRole(['admin', 'teacher'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $question = Question::find($id);

        if ($question->attachment) {

            $filePath = storage_path('app/public/' . $question->attachment);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $question->delete();

        return response()->json([
            'message' => 'question deleted successfully!'
        ]);

    }
}
