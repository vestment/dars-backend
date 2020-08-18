<?php

namespace App;
use App\Models\Auth\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Model;

class academy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_link', 'twitter_link', 'linkedin_link', 'payment_method', 'payment_details', 'description','percentage','logo','gallery','ar_description'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teachers(){
        return $this->hasMany(TeacherProfile::class,'academy_id','user_id');
    }
//    public function getDataFromColumn($col)
//    {
//        // ?? en col is returned if the ar column not found
//        return $this->attributes[app()->getLocale() == 'ar' ? 'ar_'.$col : $col] ?? $this->attributes[$col];
//    }
    // public function scopeofAcademy($query)
    // {
    //     if (!Auth::user()->isAdmin()) {
    //         return $query->whereHas('academies', function ($q) {
    //             $q->where('user_id', Auth::user()->id);
    //         });
    //     }
    //     return $query;
    // }
}
