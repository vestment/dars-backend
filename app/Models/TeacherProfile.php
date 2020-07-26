<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\academy;

class TeacherProfile extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_link', 'twitter_link', 'linkedin_link', 'payment_method', 'payment_details', 'description','academy_id','type','percentage'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->belongsTo(User::class);
    }

    public function academy(){
        return $this->belongsTo(academy::class);


    }
    public function scopeofAcademy($query)
    {
        if (!Auth::user()->isAdmin()) {
            return $query->whereHas('academy', function ($q) {
                $q->where('academy_id', Auth::user()->id);
            });
        }
        return $query;
    }
  
}
