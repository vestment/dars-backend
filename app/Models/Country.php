<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];
   public function eduSystems(){
       return $this->hasMany(EduSystem::class);
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
