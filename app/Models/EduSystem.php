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
}
