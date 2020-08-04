<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use App\Models\Chapters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class ChaptersController extends Controller
{
    public function index(Request $request)
    {
       
        $courses = $courses = Course::has('category')->ofTeacher()->pluck('title', 'id')->prepend('Please select', '');

        return view('backend.chapters.index', compact('courses'));
    }

    public function getData(Request $request)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $chapters = "";
        $chapters = Chapters::whereIn('course_id', Course::ofTeacher()->pluck('id'));


        if ($request->course_id != "") {
            $chapters = $chapters->where('course_id', (int)$request->course_id)->orderBy('created_at', 'desc')->get();
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $chapters = Chapters::query()->with('course')->orderBy('created_at', 'desc')->onlyTrashed()->get();
        }


        if (auth()->user()->can('lesson_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('lesson_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($chapters)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.chapters', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.chapters.show', ['lesson' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.chapters.edit', ['lesson' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.chapters.destroy', ['chapter' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                if (auth()->user()->can('test_view')) {
                    if ($q->test != "") {
                        $view .= '<a href="' . route('admin.tests.index', ['lesson_id' => $q->id]) . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.tests.title') . '</a>';
                    }
                }

                return $view;

            })
            ->editColumn('course', function ($q) {
                return ($q->course) ? $q->course->title : 'N/A';
            })
            ->editColumn('lesson_image', function ($q) {
                return ($q->lesson_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->lesson_image) . '">' : 'N/A';
            })
            ->editColumn('free_lesson', function ($q) {
                return ($q->free_lesson == 1) ? "Yes" : "No";
            })
            ->editColumn('published', function ($q) {
                return ($q->published == 1) ? "Yes" : "No";
            })
            ->rawColumns(['lesson_image', 'actions'])
            ->make();
    }

}
