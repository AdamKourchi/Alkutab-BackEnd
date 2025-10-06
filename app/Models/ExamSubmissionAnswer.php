<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmissionAnswer extends Model
{
    protected $fillable = ['user_id', 'exam_submission_id', "question_id", 'option_id', 'answer_text'];


    public function examSubmission()
    {
        return $this->belongsTo(ExamSubmission::class);
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class);
    }

    public function option()
    {
        return $this->belongsTo(ExamQuestionOption::class);
    }

}
