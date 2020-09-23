<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\academy;
use App\Models\Media;

/**
 * Class Course
 *
 * @package App
 * @property string $title
 * @property string $slug
 * @property text $description
 * @property decimal $price
 * @property string $course_image
 * @property string $start_date
 * @property tinyInteger $published
 */
class CourseUser extends Model
{
    
    protected $table='course_user';
    protected $fillable = ['course_id','user_id'];

    public function course()
    {
        return $this->belongsTo('\App\Models\Course' , 'course_id' );
    }
    public function user()
    {
        return $this->belongsTo('\App\Models\Auth\User' , 'user_id' );
    }

}
