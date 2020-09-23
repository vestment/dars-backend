<?php

namespace App\Http\Controllers\Backend;

use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\academy;
use App\Models\TeacherProfile;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
class ReportController extends Controller
{
    public function index() {
        $acadmies = [];
        if (auth()->user()->isAdmin()) {
            $acadmies = User::hasRole('academy')->with('acadmey')->get();
        }
    }
    public function getSalesReport()
    {
        $acadmies = [];
        $courses = [];
        $bundles = [];
        if (auth()->user()->isAdmin()) {
            $allAcadmies = User::role('academy')->with('academy')->pluck('id','first_name')->toArray();
            $acadmies = User::role('academy')->with('academy')->get();
            $academy = $acadmies[0]->user_id;
            $academyTeachersCollection= TeacherProfile::where('academy_id',$academy);
            $academyTeachers = $academyTeachersCollection->with('teacher')->get();
            $academyTeachersIds = $academyTeachersCollection->pluck('user_id');
            // Courses associated with academy teachers
            $coursesCollection = Course::withoutGlobalScope('filter')->whereHas('teachers', function ($query) use ($academyTeachersIds) {
                $query->whereIn('user_id', $academyTeachersIds);
            })->with('category');
            $coursesIds = $coursesCollection->pluck('id')->toArray();
            $academyCourses = Course::withoutGlobalScope('filter')->where('academy_id',$academy)->pluck('id')->toArray();
            $courses = Course::whereIn('id',$coursesIds)->pluck('id')->toArray();
            $courses = array_merge($courses,$academyCourses);
            $bundles = Bundle::whereHas('courses',function ($query) use ($coursesIds) {
                $query->whereIn('id',$coursesIds);
            })->pluck('id');
        } else {
            $courses = Course::ofTeacher()->pluck('id');
            $bundles = Bundle::ofTeacher()->pluck('id');
        }
        $bundle_earnings = Order::with('items')->whereHas('items',function ($q) use ($bundles){
            $q->where('item_type','=',Bundle::class)
                ->whereIn('item_id',$bundles);
        })->where('status','=',1);


        $bundle_sales = $bundle_earnings->count();
        $bundle_earnings = $bundle_earnings->sum('amount');

        $course_earnings = Order::with('items')->whereHas('items',function ($q) use ($courses){
            $q->where('item_type','=',Course::class)
                ->whereIn('item_id',$courses);
        })->where('status','=',1);

        $course_sales = $course_earnings->count();
        $course_earnings = $course_earnings->sum('amount');

        $total_earnings = $course_earnings+$bundle_earnings;
        $total_sales = $course_sales+$bundle_sales;
        return view('backend.reports.sales',compact('total_earnings','total_sales','allAcadmies'));
    }

    public function getStudentsReport()
    {
        return view('backend.reports.students');
    }

    public function getCourseData(Request $request)
    {

        $courses = Course::ofTeacher()->pluck('id');

        if (auth()->user()->hasRole('academy')) {

        // Teachers associated with academy
        $academyTeachersCollection= TeacherProfile::where('academy_id', auth()->user()->id);
        $academyTeachers = $academyTeachersCollection->with('teacher')->get();
        $academyTeachersIds = $academyTeachersCollection->pluck('user_id');
        // Courses associated with academy teachers
        $coursesCollection = Course::withoutGlobalScope('filter')->whereHas('teachers', function ($query) use ($academyTeachersIds) {
            $query->whereIn('user_id', $academyTeachersIds);
        })->with('category');
        $courses = $coursesCollection->pluck('id')->toArray();
        } elseif (auth()->user()->isAdmin()) {
            $academy = $request->acadmey_id ? $request->acadmey_id : academy::first()->user_id;
            $academyTeachersCollection= TeacherProfile::where('academy_id',$academy);
            $academyTeachersIds = $academyTeachersCollection->pluck('user_id');
            // Courses associated with academy teachers
            $courses = Course::withoutGlobalScope('filter')->whereHas('teachers', function ($query) use ($academyTeachersIds) {
                $query->whereIn('user_id', $academyTeachersIds);
            })->with('category')->pluck('id')->toArray();
            $academyCourses = Course::withoutGlobalScope('filter')->where('academy_id',$academy)->pluck('id')->toArray();
            $courses = array_merge($courses,$academyCourses);
        }
        $course_orders = OrderItem::whereHas('order',function ($q){
            $q->where('status','=',1);
        })->with('item')->where('item_type','=',Course::class)
            ->whereIn('item_id',$courses)
            ->join('courses', 'order_items.item_id', '=', 'courses.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('item_id', DB::raw('count(*) as orders, sum(orders.amount) as earnings'))
            ->groupBy('item_id')
            ->get();
        return DataTables::of($course_orders)
            ->addIndexColumn()
            ->editColumn('course', function ($q) {
                $course = Course::withoutGlobalScope('filter')->find($q->item_id);
                $course_name = $course->title;
                $course_slug = $course->slug;
                $link = "<a href='".route('courses.show', [$course_slug])."' target='_blank'>".$course_name ? $course_name : $q->item_id."</a>";
                return $link;
            })
            ->rawColumns(['course'])
            ->make();
    }

    public function getBundleData(Request $request)
    {
        $bundles = Bundle::ofTeacher()->has('students','>',0)->withCount('students')->pluck('id');

        $bundle_orders = OrderItem::whereHas('order',function ($q){
            $q->where('status','=',1);
        })->where('item_type','=',Bundle::class)
            ->whereIn('item_id',$bundles)
            ->join('bundles', 'order_items.item_id', '=', 'bundles.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('item_id', DB::raw('count(*) as orders, sum(orders.amount) as earnings'))
            ->groupBy('item_id')
            ->get();


        return \DataTables::of($bundle_orders)
            ->addIndexColumn()
            ->addColumn('bundle', function ($q) {
                $bundle_name = $q->title;
                $bundle_slug = $q->slug;
                $link = "<a href='".route('bundles.show', [$bundle_slug])."' target='_blank'>".$bundle_name."</a>";
                return $link;
            })
            ->addColumn('students',function ($q){
                return $q->students_count;
            })
            ->rawColumns(['bundle'])
            ->make();
    }

    public function getStudentsData(Request $request){
        $courses = Course::ofTeacher()->has('students','>',0)->withCount('students')->get();

        return \DataTables::of($courses)
            ->addIndexColumn()
            ->addColumn('completed', function ($q) {
                $count = 0;
                if(count($q->students) > 0){
                    foreach ($q->students as $student){
                        $completed_lessons =  $student->chapters()->where('course_id', $q->id)->get()->pluck('model_id')->toArray();
                        if (count($completed_lessons) > 0) {
                            $progress = intval(count($completed_lessons) / $q->courseTimeline->count() * 100);
                            if($progress == 100){
                                $count++;
                            }
                        }
                    }
                }
                return $count;

            })
            ->make();
    }
}
