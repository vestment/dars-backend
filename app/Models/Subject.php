<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];
    public function EduStatgeSem(){
        return $this->belongsTo(EduStageSemester::class);
    }
}
