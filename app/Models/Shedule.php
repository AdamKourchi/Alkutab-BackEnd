<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shedule extends Model
{
    function circle() {
        return $this->belongsTo(Circle::class);
    }
}
