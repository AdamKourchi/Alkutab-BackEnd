<?php

namespace App\Models;
use App\Models\ExamQuestion;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{

    protected $fillable = [
        'course_id',
        'exam_type',
        'title',
        'description',
        'is_final',
        'duration',
        'instructions',
        "start_time",
        "end_time"
    ];

     public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

         public function examSubmission()
    {
        return $this->hasMany(ExamSubmission::class);
    }
}
