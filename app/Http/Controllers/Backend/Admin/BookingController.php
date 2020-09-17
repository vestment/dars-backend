<?php

namespace App\Http\Controllers\Backend\Admin;

use App\academy;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Course;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{

    /**
     * Display a listing of Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('course_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $offlineCourses = Course::where('offline', 1)->onlyTrashed()->ofTeacher();
        } else {
            $offlineCourses = Course::where('offline', 1)->ofTeacher();
        }
        $offlineCourses = $offlineCourses->get();
        if (Auth::user()->hasRole('academy')) {
            $academyTeachersIds = Auth::user()->academy->teachers()->pluck('user_id');
            // Courses associated with academy teachers
            $coursesCollection = Course::whereHas('teachers', function ($query) use ($academyTeachersIds) {
                $query->whereIn('user_id', $academyTeachersIds);
            })->where('offline', 1);
            $offlineCourses = $coursesCollection->get();
            // Categories associated with academy courses
            $categories = Category::where('name', '911')->first();
            if ($categories) {
                $courses_911 = Course::where('category_id', $categories->id)->with('category')->get();
                $offlineCourses = $offlineCourses->merge($courses_911);
            }
        } elseif (Auth::user()->hasRole('teacher')) {
            $offlineCourses = Course::ofTeacher()
                ->whereHas('category')
                ->orderBy('created_at', 'desc')->get();
        }
        return view('backend.bookings.index', compact('offlineCourses'));
    }

    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = [];
        $courses_911 = [];
        $academyCourses = [];

        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $courses = Course::where('offline', 1)->whereHas('category')->orderBy('created_at', 'desc');

        if (request('show_deleted') == 1) {
            $courses = $courses->onlyTrashed();
        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            $courses = $courses
                ->whereHas('category')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                });
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            $courses = $courses
                ->whereHas('category')
                ->where('category_id', $id);
        } else {
            $courses = $courses
                ->whereHas('category');
        }
        if (Auth::user()->hasRole('academy')) {
            $academyTeachersIds = Auth::user()->academy->teachers()->pluck('user_id');
            // Courses associated with academy teachers
            $coursesCollection = Course::whereHas('teachers', function ($query) use ($academyTeachersIds) {
                $query->whereIn('user_id', $academyTeachersIds);
            })->where('offline', 1);
            $academyCourses = $coursesCollection->get();
            // Categories associated with academy courses
            $categories = Category::where('name', '911')->first();
            if ($categories) {
                $courses_911 = Course::where('category_id', $categories->id)->with('category')->get();
            }
        } elseif (Auth::user()->hasRole('teacher')) {
            $courses = Course::ofTeacher()
                ->whereHas('category');
        }
        if ($courses_911 && $academyCourses) {
            $courses = $academyCourses->merge($courses_911);
        } else {
            $courses = $courses->get();
        }
        if (auth()->user()->can('course_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('course_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($courses)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.courses', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.courses.show', ['course' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.courses.edit', ['course' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.courses.destroy', ['course' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                if ($q->published == 1) {
                    $type = 'action-unpublish';
                } else {
                    $type = 'action-publish';
                }

                $view .= view('backend.datatable.' . $type)
                    ->with(['route' => route('admin.courses.publish', ['course' => $q->id])])->render();
                return $view;

            })
            ->editColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
            ->editColumn('bookingData', function ($q) {
               $studentsOrders = OrderItem::where(['item_type'=>Course::class,'item_id'=>$q->id])->where('selectedTime','!=','')->where('selectedDate','!=','')->pluck('order_id');
        $studentCount = Order::whereIn('id', $studentsOrders)->where('status',1)->pluck('id')->count();
        $availableSeats = $q->seats - $studentCount;
                $bookingData = '<span class="text-dark"> ('.$studentCount.') Students Booked</span><br><span class="text-dark"> ('.$availableSeats.') Available Seats</span>';
                return $bookingData;
            })
            ->editColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-pink p-1 mr-1' >" . trans('labels.backend.courses.fields.published') . "</p>" : "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.unpublished') . "</p>";
                if (auth()->user()->isAdmin() || auth()->user()->hasRole('academy')) {
                    $text .= ($q->featured == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-warning p-1 mr-1' >" . trans('labels.backend.courses.fields.featured') . "</p>" : "";
                    $text .= ($q->trending == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-pink p-1 mr-1' >" . trans('labels.backend.courses.fields.trending') . "</p>" : "";
                    $text .= ($q->popular == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.popular') . "</p>" : "";
                }

                return $text;
            })
            ->editColumn('price', function ($q) {
                if ($q->free == 1) {
                    return trans('labels.backend.courses.fields.free');
                }
                return $q->price;
            })
            ->addColumn('category', function ($q) {
                return $q->category->name;
            })
            ->rawColumns(['teachers', 'bookingData', 'course_image', 'actions', 'status'])
            ->make();
    }
}
