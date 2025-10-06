<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    /** @use HasFactory<\Database\Factories\PathFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'created_by',
        'start_at',
        'end_at',
        'duration_months',
        'diploma_title',
        'is_active',
        "is_hifd"
    ];


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function levels()
    {
        return $this->hasMany(Level::class)->orderBy('order');
    }

    public function circles()
    {
        return $this->hasMany(Circle::class);
    }

       public function enrollmentsRequest(){
        return $this->hasMany(EnrollmentRequest::class);
    }
}
