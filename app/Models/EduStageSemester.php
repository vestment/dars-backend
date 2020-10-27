<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduStageSemester extends Model
{
    //

    public function courses(){
        return $this->belongsToMany(Course::class,'course_edu_statge_sems','edu_statge_sem_id','course_id');
    }
}
