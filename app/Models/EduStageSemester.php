<?php

namespace App\Models;
use App\Models\Year;

use Illuminate\Database\Eloquent\Model;
 
class EduStageSemester extends Model
{
    //

    public function courses(){

        $lastYear = Year::orderBy('id', 'DESC')->first();
        return $this->belongsToMany(Course::class,'course_edu_statge_sems','edu_statge_sem_id','course_id')->with('category','teachers','year')->where('year_id',$lastYear->id);
    }

    // public function semester(){

    //     return $this->belongsToMany(semester::class,'course_edu_statge_sems','edu_statge_sem_id','course_id');

    // }

   
}
