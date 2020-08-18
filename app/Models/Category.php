<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function bundles()
    {
        return $this->hasMany(Bundle::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function getDataFromColumn($col) {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() =='ar' ? 'ar_'.$col : $col] ?? null;
    }


}
