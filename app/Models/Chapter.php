<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['title', 'title_ar','slug', 'chapter_image', 'short_text', 'full_text', 'short_text_ar', 'full_text_ar','position', 'downloadable_files', 'free_chapter', 'published', 'course_id'];
    



    public function course(){

        return $this->belongsto('App\Models\Course');
    
    }

    public function test(){

        return $this->hasone('App\Models\Test');
    
    }

    public function lessons(){

        return $this->hasmany('App\Models\Lesson');
    
    }



}


