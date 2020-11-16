<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduSystem extends Model
{
    protected $guarded = [];
    public function eduStages(){
        return $this->hasMany(EduStage::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function getDataFromColumn($col) {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() =='ar' ? 'ar_'.$col : 'en_'.$col] ?? $this->attributes[$col];
    }
    public function studentData()
    {
        return $this->hasOne(studentData::class);
    }
}
