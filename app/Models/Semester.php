<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $guarded = [];
    public function eduStages(){
        return $this->belongsToMany(EduStage::class,'edu_stage_semesters');
    }
    public function subjects(){
        return $this->belongsToMany(Subject::class,'edu_stage_semesters');
    }
    public function getDataFromColumn($col) {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() =='ar' ? 'ar_'.$col : 'en_'.$col] ?? $this->attributes[$col];
    }
}
