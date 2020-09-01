<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\Media;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class LessonsController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('lesson_access')) {
            return abort(401);
        }
        $courses = Course::has('category')->ofTeacher()->pluck('title', 'id')->prepend('Please select', '');
        $allChapters = Chapter::with('course')->get();
        $chapters = ['Please Select'];
        foreach ($allChapters as $key => $chapter) {
            $chapters[$chapter->id] = $chapter->getDataFromColumn('title') . ' - ' . $chapter->course->getDataFromColumn('title');
        }

        $lessons = Lesson::get();


        return view('backend.lessons.index', compact('courses', 'chapters', 'lessons'));
    }

    /**
     * Display a listing of Lessons via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $lessons = "";
        $lessons = Lesson::whereIn('chapter_id', Chapter::pluck('id'));


        if ($request->chapter_id != "") {
            $lessons = $lessons->where('chapter_id', (int)$request->chapter_id)->orderBy('created_at', 'desc')->get();
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $lessons = Lesson::query()->with('course')->orderBy('created_at', 'desc')->onlyTrashed()->get();
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

        return DataTables::of($lessons)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.lessons', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.lessons.show', ['lesson' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.lessons.edit', ['lesson' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.lessons.destroy', ['lesson' => $q->id])])
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

    /**
     * Show the form for creating new Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        if (request()->chapter_id) {
            $course_id = Chapter::findOrFail(request()->chapter_id)->with('course')->first()->course_id;
        }
        $courses = Course::has('category')->ofTeacher()->select('title_ar', 'title', 'id')->get();
//        $allCourses = [];
//        foreach ($courses as $key => $course) {
//            $allCourses[$course->id] = $course->getDataFromColumn('title');
//        }
        $chapters = Chapter::with('course')->get();
        $allChapters = [];
        $videos = Media::where('type', 'upload')->where('model_id', null)->pluck('file_name', 'id');
        foreach ($chapters as $key => $chapter) {
            $allChapters[$chapter->id] = $chapter->getDataFromColumn('title') . ' - ' . $chapter->course->getDataFromColumn('title');
        }
        return view('backend.lessons.create', compact('videos', 'courses','course_id', 'chapters', 'allChapters'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param \App\Http\Requests\StoreLessonsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonsRequest $request)
    {
      
        
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } else if ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Lesson::where('slug', '=', $slug)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $lesson = Lesson::create($request->except('downloadable_files', 'lesson_image')
            + ['position' => Lesson::where('course_id', $request->course_id)->max('position') + 1]);

        $lesson->slug = $slug;
        $lesson->save();


        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = array_last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Lesson')
                    ->where('model_id', '=', $lesson->id)
                    ->first();
                $size = 0;

            } elseif ($request->media_type == 'upload') {
                if ($request->video_file) {
                    $media = Media::findOrFail($request->video_file);
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->save();
                } else {
                    redirect()->back()->withFlashDanger('Please select a video from the list');
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $lesson->title . ' - video';
            }
            if (($request->media_type != 'upload')) {
                if ($media == null) {
                    $media = new Media();
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = $url;
                    $media->type = $request->media_type;
                    $media->user_id = auth()->user()->id;
                    $media->file_name = $video_id;
                    $media->size = 0;
                    $media->save();
                }
            }
        }

        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

// dd($request->all());

// $chapterSeq = CourseTimeline::where('model_id',$request->chapter_id)->value('sequence');
        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {

            // $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = CourseTimeline::where('model_id',$request->chapter_id)->value('sequence');

            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->chapter_id = $request->chapter_id;
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }

        return redirect()->route('admin.courses.edit', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Lesson.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }
        $videos = [];
        $allCourses = Course::has('category')->select('title_ar', 'id', 'title')->get();
        $courses = [];
        foreach ($allCourses as $key => $course) {
            $courses[$course->id] = $course->getDataFromColumn('title');
        }
        $allchapters = Chapter::select('title_ar', 'id', 'title')->get();
        $chapters = [];
        foreach ($allchapters as $key => $chapter) {
            $chapters[$chapter->id] = $chapter->getDataFromColumn('title');
        }
        $lesson = Lesson::with('media')->findOrFail($id);
        $videos = Media::where('type', 'upload')->whereIn('model_type', [Lesson::class,''])->pluck('file_name', 'id');
                //        if ($lesson->media) {
                //            $videos = $lesson->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
                //        }

        return view('backend.lessons.edit', compact('lesson', 'courses', 'videos', 'chapters'));
    }

    /**
     * Update Lesson in storage.
     *
     * @param \App\Http\Requests\UpdateLessonsRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessonsRequest $request, $id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } else if ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Lesson::where('slug', '=', $slug)->where('id', '!=', $id)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $lesson = Lesson::findOrFail($id);
        $lesson->update($request->except('downloadable_files', 'lesson_image'));
        $lesson->slug = $slug;
        $lesson->save();

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $lesson->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->user_id = auth()->user()->id;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if ($request->video_file) {
                    $oldMedia = Media::where('model_id',$id)->where('model_type',$model_type)->first();
                    if ($oldMedia) {
                        if ($oldMedia->type == 'upload') {
                            $oldMedia->model_type = '';
                            $oldMedia->model_id = null;
                            $oldMedia->name = 'Unselected - video';
                            $oldMedia->save();
                        } else {
                            $oldMedia->delete();
                        }
                    }

                    $media = Media::findOrFail(intval($request->video_file));
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->save();

                } else {
                    redirect()->back()->withFlashDanger('Please select a video from the list');
                }
            }
        }
        if ($request->hasFile('add_pdf')) {
            $pdf = $lesson->mediaPDF;
            if ($pdf) {
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ((int)$request->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }


        return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
    }


    /**
     * Display Lesson.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();

        $lesson = Lesson::findOrFail($id);


        return view('backend.lessons.show', compact('lesson', 'tests', 'courses'));
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
        $lesson = Lesson::findOrFail($id);
        $lesson->chapterStudents()->where('course_id', $lesson->course_id)->forceDelete();
        $lesson->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Lesson at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Lesson::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Lesson from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Lesson from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);

        if (File::exists(public_path('/storage/uploads/' . $lesson->lesson_image))) {
            File::delete(public_path('/storage/uploads/' . $lesson->lesson_image));
            File::delete(public_path('/storage/uploads/thumb/' . $lesson->lesson_image));
        }

        $timelineStep = CourseTimeline::where('model_id', '=', $id)
            ->where('course_id', '=', $lesson->course->id)->first();
        if ($timelineStep) {
            $timelineStep->delete();
        }

        $lesson->forceDelete();


        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
