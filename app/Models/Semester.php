<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $guarded = [];
    public function eduStages(){
        return $this->belongsToMany(EduStage::class,'edu_stage_semester');
    }
}
