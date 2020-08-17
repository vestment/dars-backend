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

class CoursesController extends Controller
{

    private $path;

    public function __construct()
    {

        // $path = 'frontend';
        // if (session()->has('display_type')) {
        //     if (session('display_type') == 'rtl') {
        //         $path = 'frontend-rtl';
        //     } else {
        //         $path = 'frontend';
        //     }
        // } else if (config('app.display_type') == 'rtl') {
        //     $path = 'frontend-rtl';
        // }
        // $this->path = $path;

        $this->path = 'frontend';
    }

    public function all()
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

        return view($this->path . '.courses.index', compact('teacher_dat', 'teachers', 'popular_course', 'course_rating', 'teacher_data', 'chapters', 'course_lessons', 'courses', 'purchased_courses', 'recent_news', 'featured_courses', 'categories'));
    }

    public function show($course_slug)
    {
        $continue_course = NULL;
        $course_id = Course::where('slug', $course_slug)->value('id');
        // dd($course_id);
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with(['publishedLessons', 'reviews'])->firstOrFail();
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        $chapters = Chapter::where('course_id', $course_id)->get();
        // dd($teacherprofile = TeacherProfile::where('user_id',$user_id)->get('description'));

        // dd($chapters);
        $chapter_lessons = Lesson::where('course_id', $course_id)->where('published', '=', 1);
        // $chapter_lessons = Lesson::where('slug', $lesson_slug)->where('course_id', $course_id)->where('published', '=', 1)->first();
        $lessoncount = $course->lessons()->where('course_id', $course->id)->get()->count();
        $chaptercount = $course->chapters()->where('course_id', $course->id)->get()->count();


        if (($course->published == 0) && ($purchased_course == false)) {
            abort(404);
        }
        $course_rating = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        // return Course::class;
        // dd($course->reviews()->where('reviewable_id',$is_reviewed));


        if (auth()->check() && $course->reviews()->where('user_id', '=', auth()->user()->id)->first()) {
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence', 'asc');
        // dd($lessons);

        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $course_lessons = $course->lessons->pluck('id')->toArray();
            $continue_course = $course->courseTimeline()
                ->whereIn('model_id', $course_lessons)
                ->orderby('sequence', 'asc')
                ->whereNotIn('model_id', $completed_lessons)
                ->first();
            if ($continue_course == null) {
                $continue_course = $course->courseTimeline()
                    ->whereIn('model_id', $course_lessons)
                    ->orderby('sequence', 'asc')->first();
            }

        }
        $mandatory_courses = [];
        $optional_courses = [];
        if ($course->mandatory_courses && $course->optional_courses) {
            $mandatory_courses = Course::whereIn('id', json_decode($course->mandatory_courses))->get();
            $optional_courses = Course::whereIn('id', json_decode($course->optional_courses))->get();
        }

//dd($course->getDataFromColumn('title'));
        return view($this->path . '.courses.course', compact('optional_courses', 'mandatory_courses', 'chaptercount', 'chapter_lessons', 'lessoncount', 'chapters', 'course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons', 'total_ratings', 'is_reviewed', 'lessons', 'continue_course'));
    }

    public function filerCoursesByCategory(Request $request)
    {
        $courses = [];
        if ($request->type == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->with(['teachers', 'reviews'])->where('published', 1)->where('popular', '=', 1);
        } else if ($request->type == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->with(['teachers', 'reviews'])->where('published', 1)->where('trending', '=', 1);
        } else if ($request->type == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->with(['teachers', 'reviews'])->where('published', 1)->where('featured', '=', 1);
        } elseif ($request->type == 'All') {
            $courses = Course::withoutGlobalScope('filter')->with(['teachers', 'reviews'])->where('published', 1);
        }
        if (intval($request->maxPrice) && $request->maxPrice != 0) {
            $courses = $courses->where('price', '<=', intval($request->maxPrice));

        }
        if ($request->isFree != 'false') {
            $courses = $courses->where('free', 1);
        }
        if ($request->duration) {

            $cond .= ' + duration';
        }
        if (intval($request->rating) && $request->rating != 'NaN') {
            $coursesIds = [];
            foreach ($courses->orderBy('id', 'desc')->paginate(8)->items() as $course) {
                if ($course->reviews->avg('rating') >= intval($request->rating) && $course->reviews->avg('rating') != 0) {
                    array_push($coursesIds, $course->id);
                }
            }
            $courses = $courses->whereIn('id', $coursesIds);
        }
        $courses = $courses->orderBy('id', 'desc')->paginate(8);
        $html = '';
        if (count($courses->items()) > 0) {
            foreach ($courses->items() as $course) {
                $html .= '<div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2"><div class="best-course-pic-text relative-position">
                                            <div class="best-course-pic piclip relative-position"';
                if ($course->course_image != "") {
                    $html .= 'style="background-image: url(\'' . asset('storage/uploads/' . $course->course_image) . '\') ">';
                }
                $html .= '<div class="course-price text-center gradient-bg">';
                if ($course->free == 1) {
                    $html .= '<span>' . trans('labels.backend.courses.fields.free') . '</span>';
                } else {
                    $html .= ' <span> ' . getCurrency(config('app.currency'))['symbol'] . ' ' . $course->price . '</span>';
                }
                $html .= '</div></div> <div class="card-body back-im p-3"><h3 class="card-title titleofcard">' . $course->title . '</h3>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="course-rate ul-li"><ul>';

                for ($i = 0; $i < 5; ++$i) {
                    $html .= '<li><i class="fa' . ($course->rating <= $i ? 'r' : 's') . ' fa-star' . ($course->rating == $i + .5 ? '-half-alt' : '') . '" aria-hidden="true"></i></li>';
                }
                $html .= '<li><span class="text-muted">' . number_format($course->rating) . ' (' . number_format($course->reviews->count()) . ')</span></li></ul> </div></div>
                            </div>
                            <div class="course-meta my-1 vv">
                            <small>
                            <i class="far fa-clock"></i>' . $course->course_hours . '
                            hours |
                            </small>
                            <small><i class="fab fa-youtube"></i> ' . $course->chapters()->count() . ' lecture</small></div>';


                $html .= '<div class="row my-2"> <div class="col-4">';
                foreach ($course->teachers as $key => $teacher) {
                    if ($teacher->avatar_location == "") {
                        $html .= '<img class="rounded-circle teach_img"
                                                                     src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                     alt="">';
                    } else {
                        $html .= '<img class="rounded-circle teach_img"
                                                                     src="' . asset($teacher->avatar_location) . '"
                                                                     alt="">';
                    }
                }
                $html .= '</div><div class="col-8">
                                                        <div class="row">';
                foreach ($course->teachers as $key => $teacher) {
                    $teacherData = TeacherProfile::where('user_id', $teacher->id)->first();
                    $html .= '<a class="col-12"
                                                                   href="' . route('teachers.show', ['id' => $teacher->id]) . '"
                                                                   target="_blank">
                                                                    ' . $teacher->full_name . '
                                                                </a><a class="col-12"
                                                                   href="' . route('teachers.show', ['id' => $teacher->id]) . '"
                                                                   target="_blank">
                                                                      ' . $teacherData->description . '
                                                                </a>';
                }


                $html .= '</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-10 col-9">';
                if (auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get($course->id))) {
                    $html .= '<button type="submit" class="btn btn-block btn-info ">' . trans('labels.frontend.course.add_to_cart') . '<i class="fa fa-shopping-bag ml-1"></i></button>';
                } elseif (!auth()->check()) {
                    if ($course->free == 1)
                        $html .= '<a class="btn btn-block btn-info" href="' . route('login.index') . '">' . trans('labels.frontend.course.get_now') . ' <i class="fas fa-caret-right"></i></a>';
                    else {
                        $html .= '<a class="btn btn-block btn-info" href = "' . route('login.index') . '"> ' . trans('labels.frontend.course.add_to_cart') . ' <i class="fa fa-shopping-bag" ></i ></a >';
                    }
                } elseif (auth()->check() && (auth()->user()->hasRole('student'))) {
                    if ($course->free == 1) {
                        $html .= '<form action=" ' . route('cart.getnow') . ' " method = "POST"><input type="hidden" name="csrf-token" content="' . csrf_token() . '">
                                                                    <input type = "hidden" name = "course_id"
                                                                           value = "' . $course->id . '" />
                                                                    <input type = "hidden" name = "amount"
                                                                           value = "' . ($course->free == 1) ? 0 : $course->price . '"/>
                                                                    <button class="btn btn-block btn-info"
                                                                            href = "#" > ' . trans('labels.frontend.course.get_now') . '
                                                                        <i class="fas fa-caret-right" ></i >
                                                                    </button >
                                                                </form >';
                    } else {
                        $html .= '<form action = "' . route('cart.addToCart') . '"  method = "POST" > <input type="hidden" name="csrf-token" content="' . csrf_token() . '"/>  <input type = "hidden" name = "course_id" value = "' . $course->id . '" /><input type = "hidden" name = "amount" value = "' . (($course->free == 1) ? 0 : $course->price) . ' " />
                                                                    <button type = "submit"
                                                                            class="btn btn-block btn-info" >
                                                                ' . trans('labels.frontend.course.add_to_cart') . '
                                                                        <i class="fa fa-shopping-bag" ></i >
                                                                    </button >
                                                                </form >';
                    }
                }
                $html .= '</div>  <div class=""> <a href="' . route('courses.show', [$course->slug]) . '"  class="btn btnWishList">
                                                            <i class="far fa-bookmark"></i>
                                                        </a>
                                                    </div> </div> </div>  </div> </div>';
            }
        } else {
            $html = '<div class="col-12  d-flex justify-content-center"><div class=""><div class="alert-danger alert"> No courses found </div><img src="' . url('img/frontend/user/lost.svg') . '"></div></div>  ';
        }
        return $html;
    }

    public function rating($course_id, Request $request)
    {
        $course = Course::findOrFail($course_id);
        $course->students()->updateExistingPivot(\Auth::id(), ['rating' => $request->get('rating')]);

        return redirect()->back()->with('success', 'Thank you for rating.');
    }


    public function getByCategory(Request $request)
    {

        $category = Category::where('slug', '=', $request->category)
            ->where('status', '=', 1)
            ->first();
        $categories = Category::where('status', '=', 1)->get();

        if ($category != "") {
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            if (request('type') == 'popular') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'trending') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'featured') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(9);
            }
            $popular_course = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);
            $trending_courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);
            $teacher_data = TeacherProfile::get();
            $teachers = User::get();
            // dd($teacher);
            $cour = Course::with('teachers')->get();
            //  dd($cour);
            return view($this->path . '.courses.index', compact('courses', 'teachers', 'teacher_data', 'cour', 'popular_course', 'trending_courses', 'category', 'recent_news', 'featured_courses', 'categories'));
        }
        return abort(404);
    }


    // public function try(Request $request)
    // {

    //             $popular_courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);


    //             $trending_courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);


    //         return view( $this->path.'.courses.index', compact('courses', 'category', 'recent_news','featured_courses','categories'));


    // }

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = Course::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = Course::class;
        $review->rating = $request->rating;
        $review->content = $request->review;
        $review->save();

        return back();
    }

    public function editReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $course = $review->reviewable;
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            $course_rating = 0;
            $total_ratings = 0;
            $lessons = $course->courseTimeline()->orderby('sequence', 'asc')->get();
            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
            if (\Auth::check()) {

                $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
                $continue_course = $course->courseTimeline()->orderby('sequence', 'asc')->whereNotIn('model_id', $completed_lessons)->first();
                if ($continue_course == "") {
                    $continue_course = $course->courseTimeline()->orderby('sequence', 'asc')->first();
                }

            }
            return view($this->path . '.courses.course', compact('course', 'purchased_course', 'recent_news', 'completed_lessons', 'continue_course', 'course_rating', 'total_ratings', 'lessons', 'review'));
        }
        return abort(404);

    }


    public function updateReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();

            return redirect()->route('courses.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('courses.show', ['slug' => $slug]);
        }
        return abort(404);
    }


}
