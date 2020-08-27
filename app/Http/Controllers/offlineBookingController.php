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
use App\academy;

class offlineBookingController extends Controller
{
    public function index()
    {

        $courses = Course::with(['academy','category'])->withoutGlobalScope('filter')->where('offline', 1)->orderBy('id', 'desc')->get();
   
        $teacher_filtering=[];
        foreach($courses as $course) {
            foreach ($course->teachers as $teacher) {
                // $teacher_data = TeacherProfile::where('user_id', $teacher->id)->get();
                if (!in_array($teacher,$teacher_filtering)){
                array_push($teacher_filtering,$teacher);
                }
            }
        }
        $teach_filtering=$teacher_filtering;


        $academy_filter=[];
        // foreach($courses as $course) {
        //    $academy = $course->academy->with('user')->first();
        //         $academy->user->assoc_id = $course->academy_id;
        //         if (!in_array($academy->user,$academy_filter)){
        //         array_push($academy_filter,$academy->user);
        //         }
        // }
        $ac_filter= $academy_filter;
        

        $category_filter=[];
        foreach($courses as $course) {
           $category = $course->category;
                
                if (!in_array($category,$category_filter)){
                array_push($category_filter,$category);
                }
        }
        $cate_filter= $category_filter;



        foreach ($courses as $course) {
            foreach ($course->teachers as $teacher) {
                $teacher_data = TeacherProfile::where('user_id', $teacher->id)->get();
            }

        }

       
        $teacher_dat = TeacherProfile::get();
        $teachers = User::get();

        return view('frontend.offlineBookingCourse', compact( 'courses','ac_filter','teacher_dat', 'teachers', 'teacher_data','teach_filtering','cate_filter'));

    }

    public function filerCoursesByCategory(Request $request)
    {
        
        $courses = [];
        $courses = Course::with(['academy','category'])->withoutGlobalScope('filter')->where('offline', 1);
       
        if ($request->categories) {
            $courses = $courses->whereIn('category_id', $request->categories);
        }

        if ($request->academies) {
            $courses = $courses->whereIn('academy_id', $request->academies);
        }
        
        if ($request->teachers) {
            $teachersIds = $request->teachers;
            $courses = $courses->whereHas('teachers', function ($query) use ($teachersIds) {
            $query->whereIn('user_id', $teachersIds);
        });
        }

        if (intval($request->maxPrice) && $request->maxPrice != 0) {
            $courses = $courses->where('price', '<=', intval($request->maxPrice));
        }   

        if ($request->isFree != 'false') {
            $courses = $courses->where('free', 1);
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
                $html .= '</div></div> <div class="card-body back-im p-3"><h3 class="card-title titleofcard">' . $course->getDataFromColumn('title') . '</h3>
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
}
