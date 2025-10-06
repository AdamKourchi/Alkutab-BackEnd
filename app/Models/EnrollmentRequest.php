<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentRequest extends Model
{
       protected $fillable = [
        'user_id',
        'path_id',
        'education_level',
        'age',
        'goal',
        'status',
        'education_level',
        'memorization_capability',
        'type'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function path()
    {
        return $this->belongsTo(Path::class);
    }
}
  
          