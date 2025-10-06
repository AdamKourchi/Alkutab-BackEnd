<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;


    // Student: relation to student_path entries
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function enrollmentsRequests(){
        return $this->hasMany(EnrollmentRequest::class);
    }

    
    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class); 
    }

    public function examSubmissions(){
        return $this->hasMany(ExamSubmission::class); 

    }


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'invite_token',
        'invite_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    // If Needed Profiles :
    // public function teacherProfile(): HasOne { ... }
    // public function studentProfile(): HasOne { ... }
}
