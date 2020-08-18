<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
                 
class Chapter extends Model
{
    protected $fillable = ['title', 'title_ar','slug', 'short_text', 'full_text', 'short-text-ar', 'full-text-ar','position', 'downloadable_files', 'free_chapter', 'published', 'course_id'];
    



    public function course(){

        return $this->belongsto('App\Models\Course');
    
    }

    public function test(){

        return $this->hasone('App\Models\Test');
    
    }

    public function lessons(){

        return $this->hasmany('App\Models\Lesson');
    
    }
    public function getDataFromColumn($col) {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() =='ar' ? $col.'_ar' : $col] ?? null;
    }


}


