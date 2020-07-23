<?php

namespace App;
use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Model;

class academy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_link', 'twitter_link', 'linkedin_link', 'payment_method', 'payment_details', 'description'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->hasmany(TeacherProfile::class);
    }
}
