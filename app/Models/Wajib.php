<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wajib extends Model
{
    protected $fillable = [
        'record_id',
        'due_date',
        'completed',
        'mark',
        'surat',
        'from_aya',
        'to_aya',
        'isRev'
    ];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
