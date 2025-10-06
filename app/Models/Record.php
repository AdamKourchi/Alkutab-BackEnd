<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }


    public function wajibs(){
        return $this->hasMany(Wajib::class);
    }
}
