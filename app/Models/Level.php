<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /** @use HasFactory<\Database\Factories\LevelFactory> */
    use HasFactory;



 

    protected $fillable = [
        'name',
        "start_at",
        "end_at",
        "duration_months",
        'description',
        'order',
        'path_id',
   
    ];

    public function path()
    {
        return $this->belongsTo(Path::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

}
