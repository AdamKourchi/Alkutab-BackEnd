<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    /** @use HasFactory<\Database\Factories\ClassSessionFactory> */
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
    ];

    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
];





    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
