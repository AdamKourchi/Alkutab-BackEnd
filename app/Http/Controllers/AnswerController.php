<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function student_submit(Request $request)
    {
        $question = Question::find($request->question_id);

        if ($question) {
            $answer = Answer::create([
                'user_id' => $request->user()->id,
                'question_id' => $request->question_id,
                'answer' => $request->answer,
            ]);

            return response()->json($answer);
        }

        return response()->json("No Question was found with that id.");



    }

    public function updateCorrection($id,Request $request){
      
        $request->validate([
            'teacher_comment' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:100',
        ]);
    
        $answer = Answer::findOrFail($id);
        $answer->teacher_comment = $request->teacher_comment;
        $answer->score = $request->score;
        $answer->save();
    
        return response()->json(['message' => 'Correction saved']);
    }
}
