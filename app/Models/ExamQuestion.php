<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
      protected $fillable = [
        'exam_id',
        'type',
        'question',
        'correct_answer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(ExamQuestionOption::class);
    }


}
