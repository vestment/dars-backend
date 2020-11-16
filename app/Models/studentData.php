<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;


class studentData extends Model
{
    protected $table = "student_data";
    protected $fillable = [
        'user_id', 'country_id', 'edu_system_id', 'edu_stage_id'
        
    ];
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function EduSys()
    {
        return $this->belongsTo(EduSystem::class);
    }
    public function EduStage()
    {
        return $this->belongsTo(EduStage::class);
    }

}
