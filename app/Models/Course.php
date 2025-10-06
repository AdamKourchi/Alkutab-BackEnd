<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;


    protected $fillable = [
        'level_id',
        'user_id',
        'title',
        'description',
        'order',
    ];



    
    public function teacher() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    

}
