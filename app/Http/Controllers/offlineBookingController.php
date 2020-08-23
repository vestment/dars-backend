<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;
use App\Models\TeacherProfile;
use App\Models\Chapter;
use App\Models\Auth\User;

class offlineBookingController extends Controller
{
    public function index()
    {
        
        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(9);
        }
        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        $categories = Category::where('status', '=', 1)->get();

        if (\Auth::check()) {
            $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }

        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $chapters = Course::with('chapters')->get();
        //  dd($chapters);
        foreach ($courses as $course) {
            foreach ($course->teachers as $teacher) {
                $teacher_data = TeacherProfile::where('user_id', $teacher->id)->get();
            }

        }

        $course_rating = 0; // Default value
        $course_lessons = null; // Not Used but exported in the return
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $teacher_dat = TeacherProfile::get();
        $teachers = User::get();
        $popular_course = Course::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);
dd($courses);
        return view('frontend.offlineBookingCourse', compact('teacher_dat', 'teachers', 'popular_course', 'course_rating', 'teacher_data', 'chapters', 'course_lessons', 'courses', 'purchased_courses', 'recent_news', 'featured_courses', 'categories'));

    }
}
