<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use App\Models\Chapter;

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
    use FileUploadTrait;
    public function index(Request $request)
    {
       
        $courses = $courses = Course::has('category')->ofTeacher()->pluck('title', 'id')->prepend('Please select', '');

        return view('backend.chapters.index', compact('courses'));
    }

    public function getChapterContent(Request $requset)
    {
        $timeline =  CourseTimeline::where('chapter_id', '=', $requset->chapter_id);
        
        $content = $timeline->model_type::where('id', '=', $timeline->model_id);
    }

    public function getData(Request $request)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $chapters = "";
        $chapters = Chapter::whereIn('course_id', Course::ofTeacher()->pluck('id'));


        if ($request->course_id != "") {
            $chapters = $chapters->where('course_id', (int)$request->course_id)->orderBy('created_at', 'desc')->get();
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $chapters = Chapter::query()->with('course')->orderBy('created_at', 'desc')->onlyTrashed()->get();
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
                        $view .= '<a href="' . route('admin.tests.index', ['chapter_id' => $q->id]) . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.tests.title') . '</a>';
                    }
                }

                return $view;

            })
            ->editColumn('course', function ($q) {
                return ($q->course) ? $q->course->title : 'N/A';
            })
            ->editColumn('chapter_image', function ($q) {
                return ($q->chapter_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->chapter_image) . '">' : 'N/A';
            })
            ->editColumn('free_lesson', function ($q) {
                return ($q->free_lesson == 1) ? "Yes" : "No";
            })
            ->editColumn('published', function ($q) {
                return ($q->published == 1) ? "Yes" : "No";
            })
            ->rawColumns(['chapter_image', 'actions'])
            ->make();
    }
    public function create()
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        $courses = Course::all();
        $newCourses = [];
            foreach($courses as $course){
                $newCourses[$course->id] = $course->getDataFromColumn('title');
          
            }


        return view('backend.chapters.create', compact('newCourses'));
    }
    public function store(Request $request)
    {
      
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        }else if($request->slug != null){
            $slug = $request->slug;
        }

        $slug_chapter = Chapter::where('slug','=',$slug)->first();
        if($slug_chapter != null){
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $chapter = Chapter::create($request->except('downloadable_files', 'chapter_image')
            + ['position' => Chapter::where('course_id', $request->course_id)->max('position') + 1]);

        $chapter->slug = $slug;
        $chapter->save();




        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Chapter::class;
            $model_id = $chapter->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $chapter->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = array_last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Chapter')
                    ->where('model_id', '=', $chapter->id)
                    ->first();
                $size = 0;

            } elseif ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Chapter')
                        ->where('model_id', '=', $chapter->id)
                        ->first();
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $chapter->title . ' - video';
            }

            if ($media == null) {
                $media = new Media();
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }
        }

        $request = $this->saveAllFiles($request, 'downloadable_files', Chapter::class, $chapter);

        if (($request->slug == "") || $request->slug == null) {
            $chapter->slug = str_slug($request->title);
            $chapter->save();
        }

        $sequence = 1;
        if (count($chapter->course->courseTimeline) > 0) {
            $sequence = $chapter->course->courseTimeline->max('sequence');

            // $sequence = CourseTimeline::where('course_id',$request->course_id)->where('model_type',Lesson::class)->orderBy('id', 'desc')->value('sequence');
         
            $sequence = $sequence + 1;
        }

        if ($chapter->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Chapter::class)
                ->where('model_id', '=', $chapter->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->model_id = $chapter->id;
            $timeline->model_type = Chapter::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }

        return redirect()->route('admin.courses.edit', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();

        $lesson = Chapter::findOrFail($id);


        return view('backend.chapters.show', compact('lesson', 'tests', 'courses'));
    }
    /**
     * Remove Lesson from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $chapter = Chapter::findOrFail($id);
        $chapter->lessons()->where('course_id', $chapter->course_id)->forceDelete();
        $chapter->test()->forceDelete();
        $chapter->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }
   
}
