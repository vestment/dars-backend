<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduStage extends Model
{
    protected $guarded = [];
    public function system(){
        return $this->belongsTo(EduSystem::class,'edu_system_id');
    }
    public function semesters(){
        return $this->belongsToMany(Semester::class,'edu_stage_semesters');
    }
    public function getDataFromColumn($col) {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() =='ar' ? 'ar_'.$col : 'en_'.$col] ?? $this->attributes[$col];
    }
}
