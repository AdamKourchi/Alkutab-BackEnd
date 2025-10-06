<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function questionOptions()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function student() {
        return $this->belongsTo(User::class,'user_id');
    }
}
