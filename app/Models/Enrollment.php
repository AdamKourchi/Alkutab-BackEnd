<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{


    protected $fillable = [
        'user_id',
        'path_id',
        'level_id',
        'record_id',
        'enrolled_at',
        'status',
        'final_score',
        'graduated',
        'circle_id'
    ];
    public function path()
    {
        return $this->belongsTo(Path::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, );
    }

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

       public function circle()
    {
        return $this->belongsTo(Circle::class);
    }
}
