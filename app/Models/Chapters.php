<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    protected $fillable = ['title', 'slug', 'chapter_image', 'short_text', 'full_text', 'position', 'downloadable_files', 'free_chapter', 'published', 'course_id'];
    



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


