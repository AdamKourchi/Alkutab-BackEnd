<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{

    protected $fillable = [
        'title',
        'user_id',
        'path_id',
        'days_of_week',
        'start_time',
        'end_time',
        'status',
        'link'
    ];
    function path()
    {
        return $this->belongsTo(Path::class);
    }

    function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function shedules()
    {
        return $this->hasMany(Shedule::class);
    }
}
