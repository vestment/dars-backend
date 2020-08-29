<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.reviews.index');
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $reviews = "";
        $courses_id = Course::has('reviews')->pluck('id')->toArray();
        if (auth()->user()->hasRole('teacher')) {
            $courses_id = auth()->user()->courses()->has('reviews')->pluck('id')->toArray();
        }
        $reviews = Review::where('reviewable_type','App\Models\Course')
            ->whereIn('reviewable_id',$courses_id)
            ->orderBy('created_at', 'desc')
            ->get();


        return DataTables::of($reviews)
            ->addIndexColumn()
            ->editColumn('time', function ($q) {
                return $q->created_at;
            })
            ->addColumn('course', function ($q) {
               $course_name = $q->reviewable->title;
               $course_slug = $q->reviewable->slug;
               $link = "<a href='".route('courses.show', [$course_slug])."' target='_blank'>".$course_name."</a>";
               return $link;
            })
            ->addColumn('user',function ($q){
                return $q->user->full_name;
            })->editColumn('active', function ($q) {
                $html = html()->label(html()->checkbox('')->id($q->id)
                        ->checked(($q->active == 1) ? true : false)->class('switch-input')->attribute('data-id', $q->id)->value(($q->active == 1) ? 1 : 0) . '<span class="switch-label"></span><span class="switch-handle"></span>')->class('switch switch-lg switch-3d switch-primary');
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
            ->rawColumns(['course'])
            ->make();
    }
    /**
     * Update review status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    public function updateStatus()
    {
        $review = Review::find(request('id'));
        $review->active = $review->active == 1 ? 0 : 1;
        $review->save();
    }
}
