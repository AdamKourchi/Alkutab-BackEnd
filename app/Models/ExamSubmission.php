<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'teacher_comment', 'score'];

        public function student()
    {
        return $this->belongsTo(User::class , 'user_id' );
    }

     public function examSubmissionAnswers()
    {
        return $this->hasMany(ExamSubmissionAnswer::class);
    }

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
}
