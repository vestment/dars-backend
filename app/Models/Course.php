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
class Course extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id','academy_id','offline','offline_price','seats','date','course_hours','learned','learned_ar','title_ar','description_ar','meta_title_ar','meta_description_ar','meta_keywords_ar', 'title', 'slug', 'description', 'price', 'course_image', 'course_video', 'start_date', 'published', 'free', 'featured', 'trending', 'popular', 'meta_title', 'meta_description', 'meta_keywords', 'knowledge', 'learned','optional_courses', 'mandatory_courses','online'];

    protected $appends = ['image'];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->whereHas('teachers', function ($q) {
                        $q->where('course_user.user_id', '=', auth()->user()->id);
                    });
                });
            }
        }

        static::deleting(function ($course) { // before delete() method call this
            if ($course->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                    File::delete(public_path('/storage/uploads/' . $course->course_image));
                    File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
                }
            }
        });


    }
    public function getImageAttribute()
    {
        if ($this->course_image != null) {
            if(file_exists('storage/uploads/' . $this->course_image)) {
                return url('storage/uploads/' . $this->course_image);
            } else {
                return url('storage/uploads/default_course_image.jpg');
            }
        }
        return url('storage/uploads/default_course_image.jpg');
    }

    public function getPriceAttribute()
    {
        if (($this->attributes['price'] == null)) {
            return round(0.00);
        }
        return $this->attributes['price'];
    }


    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : null;
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setStartDateAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['start_date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['start_date'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getStartDateAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user')->withPivot('user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')->withTimestamps()->withPivot(['rating', 'wishlist']);
    }


    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->where('published', 1)->orderBy('position');
    }

    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('published', 1);
    }

    public function scopeOfTeacher($query)
    {
        if (!Auth::user()->isAdmin()) {
            if (Auth::user()->hasRole('academy')) {
                $academyTeachersIds = TeacherProfile::where('academy_id', Auth::user()->id)->pluck('user_id');
                return $query->whereHas('teachers', function ($q) use ($academyTeachersIds) {
                    $q->whereIn('user_id', $academyTeachersIds);
                });
            }
            return $query->whereHas('teachers', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class,'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tests()
    {
        return $this->hasMany('App\Models\Test');
    }

    public function courseTimeline()
    {
        return $this->hasMany(CourseTimeline::class);
    }

    public function getIsAddedToCart()
    {
        if (auth()->check() && (auth()->user()->hasRole('student')) && (\Cart::session(auth()->user()->id)->get($this->id))) {
            return true;
        }
        return false;
    }


    public function getDataFromColumn($col)
    {
        // ?? null return if the column not found
        return $this->attributes[app()->getLocale() == 'ar' ? $col . '_ar' : $col] ?? $this->attributes[$col];
    }


    public function getDurationAttribute()
    {
        $lessonsIds = $this->lessons()->where('published', 1)->pluck('id');
        $duration = Media::where('model_type','App\Models\Lesson')->whereIn('model_id',$lessonsIds)->sum('duration');
        return sprintf("%s Hours %s Minutes", date("H", $duration), date("i", $duration));

    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    public function progress($student = null)
    {
        $main_chapter_timeline = $this->lessons()->where('published', 1)->pluck('id')->merge($this->tests()->pluck('id'));
        $completed_lessons = auth()->user()->chapters()->where('course_id', $this->id)->pluck('model_id');
        if ($student) {
            $completed_lessons = $student->chapters()->where('course_id', $this->id)->pluck('model_id');
        }
        if ($main_chapter_timeline->count() > 0) {
             return intval($completed_lessons->count() / $main_chapter_timeline->count() * 100);
        } else {
            return 0;
        }
    }

    public function isUserCertified()
    {
        $status = false;
        $certified = auth()->user()->certificates()->where('course_id', '=', $this->id)->first();
        if ($certified != null) {
            $status = true;
        }
        return $status;
    }

    public function item()
    {
        return $this->morphMany(OrderItem::class, 'item');
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_courses');
    }

    public function chapterCount()
    {
        $timeline = $this->courseTimeline;
        $chapters = 0;
        foreach ($timeline as $item) {
            if (isset($item->model) && ($item->model->published == 1)) {
                $chapters++;
            }
        }
        return $chapters;
    }

    public function mediaVideo()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed'];

        return $this->morphOne(Media::class, 'model')

            ->whereIn('type', $types);

    }

    public function chapters()
    {
        return $this->hasMany('App\Models\Chapter');

    }

    public function test()
    {

        return $this->belongsto('App\Models\Chapter');

    }
    public function academy()
    {
        return $this->belongsto('App\academy');
    }


}
