<?php

namespace App\Http\Controllers;

use App\academy;
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
use App\Models\Order;
use App\Models\OrderItem;
class CoursesController extends Controller
{

    private $path;

    public $hidden_data = [
        'courses' => [],
        'teachers' => []
    ];
    public function __construct()
    {
        $academy_911 = 29;
        $academy = academy::where('user_id', $academy_911)->with(['user', 'courses', 'teachers'])->first();
        if ($academy) {
            $academyTeachersIds = $academy->teachers()->pluck('user_id');
            $coursesIds = Course::withoutGlobalScope('filter')->whereHas('teachers', function ($query) use ($academyTeachersIds) {
                $query->whereIn('user_id', $academyTeachersIds);
            })->where('published',1)->pluck('id');
            $categories = Category::where('name','911')->first();
            $courses_911 = Course::withoutGlobalScope('filter')->where('category_id', $categories->id )->where('published', 1)->with('category')->pluck('id');
            // Merge the courses into 1 variable
            $coursesIds = $coursesIds->merge($courses_911);
            $this->hidden_data = [
                'courses' => $coursesIds,
                'teachers' => $academyTeachersIds
            ];

        }

    }

    public function all()
    {

        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->with(['teachers', 'reviews','chapters'])->where('published', 1)->where('online', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->with(['teachers', 'reviews','chapters'])->where('published', 1)->where('online', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->with(['teachers', 'reviews','chapters'])->where('published', 1)->where('online', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else {
            $courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->with(['teachers', 'reviews','chapters'])->where('online', 1)->where('published', 1)->orderBy('id', 'desc')->get();
        }
// dd($courses);
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

        $featured_courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)
            ->where('featured', '=', 1)->take(8)->get();
        $trending_courses = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $course_lessons = null; // Not Used but exported in the return
        $teacher_dat = TeacherProfile::get();
        $teachers = User::get();
        $popular_course = Course::withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->with(['teachers', 'reviews'])->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);
        return view('frontend.courses.index', compact('teacher_dat', 'trending_courses','teachers', 'popular_course', 'course_lessons', 'courses', 'purchased_courses', 'recent_news', 'featured_courses', 'categories'));
    }

    public function show($course_slug)
    {
        $continue_course = NULL;
        $course_id = Course::where('slug', $course_slug)->value('id');
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with(['publishedLessons', 'academy', 'reviews'])->firstOrFail();
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        $chapters = $course->chapters()->where('course_id', $course->id)->get();

        // Related courses will be from same category even if the academy is 911
        $related_courses = Course::where('category_id', $course->category->id)->withoutGlobalScope('filter')->with('teachers')->where('id', '!=', $course_id)->take(2)->get();

        $chapter_lessons = Lesson::where('course_id', $course_id)->where('published', '=', 1);
        $lessoncount = $course->lessons()->where('course_id', $course->id)->get()->count();
        $chaptercount = $course->chapters()->where('course_id', $course->id)->get()->count();
        $academy = $course->academy ? $course->academy->with('user')->first()->user : null;

        if (($course->published == 0)) {
            abort(404);
        }
        $course_rating = 0;
        $total_ratings = 0;
        $course_review = [];
        $completed_lessons = "";
        $is_reviewed = false;
        if (auth()->check() && $course->reviews()->where('user_id', '=', auth()->user()->id)->first()) {
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $course_review = $course->reviews()->where('active', 1)->get();
            $total_ratings = $course->reviews()->where('rating', '!=', "")->where('active', 1)->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence', 'asc');
        $lessonsMedia = Lesson::where('course_id', $course_id)->get();
        $fileCount = 0;
        foreach ($lessonsMedia as $lesson) {
            $fileCount += count($lesson->downloadableMedia);
        }
        $course_hours = Course::where('course_hours', $course_slug);

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
                    ->where('model_type', Lesson::class)
                    ->orderby('sequence', 'asc')->first();
            }

        }
        $mandatory_courses = [];
        $optional_courses = [];
        $course_date =json_encode([]);
        if ($course->optional_courses) {
            $optional_courses = Course::whereIn('id', json_decode($course->optional_courses))->withoutGlobalScope('filter')->get();
        }
        if ($course->mandatory_courses) {
            $mandatory_courses = Course::whereIn('id', json_decode($course->mandatory_courses))->withoutGlobalScope('filter')->get();
        }
        if ($course->date) {
            $course_date = $course->date ? json_decode($course->date) : null;
        }
        if ($course->learned == 'null' || $course->learned == null) {
            $course->learned = json_encode([]);
        }
       $studentsOrders = OrderItem::where(['item_type'=>Course::class,'item_id'=>$course->id])->where('selectedTime','!=','')->where('selectedDate','!=','')->pluck('order_id');
        $studentCount = Order::whereIn('id', $studentsOrders)->where('status',1)->pluck('id')->count();
        $availableSeats = $course->seats - $studentCount;
        return view('frontend.courses.course', compact('availableSeats','academy', 'course_review', 'fileCount', 'course_hours', 'related_courses', 'optional_courses', 'mandatory_courses', 'chaptercount', 'chapter_lessons', 'lessoncount', 'chapters', 'course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons', 'total_ratings', 'is_reviewed', 'lessons', 'continue_course','course_date'));
    }

    public function filerCoursesByCategory(Request $request)
    {
        $courses = [];
        $category = Category::where('slug', '=', $request->category)
            ->where('status', '=', 1)
            ->first();
        $courses = Course::where('category_id', $request->category)->whereNotIn('id', $this->hidden_data['courses'])->withoutGlobalScope('filter')->with(['teachers', 'reviews']);
        if ($request->type == 'popular') {
            $courses = $courses->where('popular', '=', 1);
        } else if ($request->type == 'trending') {
            $courses = $courses->where('trending', '=', 1);
        } else if ($request->type == 'featured') {
            $courses = $courses->where('featured', '=', 1);
        } elseif ($request->type == 'All') {
            $courses = $courses->where('published', 1);
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
                $html .= '<div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2"><div class="best-course-pic-text relative-position"><a href="'.route('courses.show', [$course->slug]).'"><div class="best-course-pic piclip relative-position" style="background-image: url(' . $course->image . ')">';

                $html .= '';

                $html .= '<div class="course-price text-center gradient-bg">';
                if ($course->free == 1) {
                    $html .= '<span>' . trans('labels.backend.courses.fields.free') . '</span>';
                } else {
                    $html .= '<span>' . (app()->getLocale() == 'ar' ? 'ج م' : 'EGP') . ' ' . $course->price . '</span>';
                }
                $html .= '</div></div></a> <div class="card-body back-im p-3"><a href="'.route('courses.show', [$course->slug]).'"><h3 class="card-title titleofcard">' . $course->getDataFromColumn('title') . '</h3></a>
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


                $html .= '<div class="row tech-height my-2"> <div class="col-4">';
                foreach ($course->teachers as $key => $teacher) {
                    if ($teacher->picture == "") {
                        $html .= '<img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg" alt="">';
                    } else {
                        $html .= '<img class="rounded-circle teach_img" src="' . asset($teacher->picture) . '" alt="">';
                    }
                }
                $html .= '</div><div class="col-8">
                                                        <div class="row">';
                foreach ($course->teachers as $key => $teacher) {
                    if ($key == 0) {
                        if ($teacher->hasRole('teacher')) {
                            $teacherData = TeacherProfile::where('user_id', $teacher->id)->first();
                            $html .= '<a class="text-pink" href="' . route('teachers.show', ['id' => $teacher->id]) . '" target="_blank">
                            ' . $teacher->full_name . '
                        </a><span class="text-muted teacher-title">
                              ' . $teacherData->getDataFromColumn('title') . '
                        </span>';
                        } elseif ($teacher->hasRole('academy')) {
                            $html .= '<a class="text-pink" href="' . route('academy.show', ['id' => $teacher->id]) . '" target="_blank">
                            ' . $teacher->full_name . '
                        </a>';
                        }
                    }
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
                $courses = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'trending') {
                $courses = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'featured') {
                $courses = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

            } else {
                $courses = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->orderBy('id', 'desc')->paginate(9);
            }
            $popular_course = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);
            $trending_courses = $category->courses()->withoutGlobalScope('filter')->whereNotIn('id', $this->hidden_data['courses'])->where('published', 1)->where('online', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);
            $categoryTeachers = [];
            foreach ($courses as $course) {
                foreach ($course->teachers as $teacher) {
                    if (!in_array($teacher->id, $categoryTeachers)) {
                        array_push($categoryTeachers, $teacher->id);
                    }
                }
            }
            $teachers = User::role('teacher')->whereIn('id',$categoryTeachers)->get();
            $teacher_data = TeacherProfile::get();
            $cour = Course::with('teachers')->whereNotIn('id', $this->hidden_data['courses'])->get();
            return view('frontend.courses.index', compact('courses', 'teacher_data', 'teachers', 'cour', 'popular_course', 'trending_courses', 'category', 'recent_news', 'featured_courses', 'categories'));
        }
        return abort(404);
    }

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = Course::withoutGlobalScope('filter')->findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = Course::class;
        $review->rating = $request->rating ?? null;
        $review->content = $request->review;
        $review->active = 0;
        $review->save();

        return redirect()->route('courses.show',['slug'=>$course->slug]);
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
            return view('frontend.courses.course', compact('course', 'purchased_course', 'recent_news', 'completed_lessons', 'continue_course', 'course_rating', 'total_ratings', 'lessons', 'review'));
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

    public function bookOfflineCourse(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
       
            $product = Course::withoutGlobalScope('filter')->findOrFail($request->course_id);
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {
            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title,$request->amount ? $request->amount : $product->price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers,
                        'selectedDate' => $request->selectedDate,
                        'selectedTime' => $request->selectedTime,
                        'offlinePrice' => $request->amount,
                    ]);
        }
        Session::flash('success', trans('labels.frontend.cart.product_added'));
        return redirect()->back();
    }

    public function player($slug,$type = null){

        return view('frontend.player',compact('slug','type'));

    }



}
