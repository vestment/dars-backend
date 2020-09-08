<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\CourseTimeline;
use App\Models\Media;
use App\Models\Courses;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoursesRequest;
use App\Http\Requests\Admin\UpdateCoursesRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use phpDocumentor\Reflection\Types\Null_;
use Yajra\DataTables\Facades\DataTables;

class CoursesController extends Controller
{
    use FileUploadTrait;

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
            $courses = Course::onlyTrashed()->ofTeacher()->get();
        } else {
            $courses = Course::ofTeacher()->get();
        }

        return view('backend.courses.index', compact('courses'));
    }

    public function editcontent()
    {

        return view('backend.courses.editcontent');
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $courses = Course::onlyTrashed()
                ->whereHas('category')
                ->ofTeacher()->orderBy('created_at', 'desc')->get();

        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                })->orderBy('created_at', 'desc')->get();
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->where('category_id', $id)->orderBy('created_at', 'desc')->get();
        } else {
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->orderBy('created_at', 'desc')->get();
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
            ->addColumn('lessons', function ($q) {
                $lesson = '<a href="' . route('admin.lessons.create', ['course_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.courses.courseContent', ['course_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
                return $lesson;
            })
            ->editColumn('course_image', function ($q) {
                return ($q->course_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->course_image) . '">' : 'N/A';
            })
            ->editColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-pink p-1 mr-1' >" . trans('labels.backend.courses.fields.published') . "</p>" : "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.unpublished') . "</p>";
                if (auth()->user()->isAdmin()) {
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
            ->rawColumns(['teachers', 'lessons', 'course_image', 'actions', 'status'])
            ->make();
    }


    /**
     * Show the form for creating new Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('course_create')) {
            return abort(401);
        }

        $allAcademies = User::role('academy')->with('academy')->get();
        $academies = [];
        foreach ($allAcademies as $academy) {
            $academies[$academy->id] = $academy->full_name;
        }
        $learned = [];
        $courses = Course::pluck('title', 'id');
        $coursesRaw = Course::all();

        foreach ($coursesRaw as $course) {
            if ($course->learned && $course->learned != 'null') {
                foreach (json_decode($course->learned) as $learnItem) {
                    $learned[$learnItem] = $learnItem;
                }
            }
        }
        $learned_ar = [];
        foreach ($coursesRaw as $course) {
            if ($course->learned_ar && $course->learned_ar != 'null') {
                foreach (json_decode($course->learned_ar) as $learnItem) {
                    $learned_ar[$learnItem] = $learnItem;
                }
            }
        }
        $teachersToSelect = [];
        $allTeachers = User::role('teacher')->select('ar_first_name', 'ar_last_name', 'first_name', 'last_name', 'id')->get();

        foreach ($allTeachers as $key => $teachers) {
            $teachersToSelect[$teachers->id] = $teachers->full_name;
        }
        $allCategories = Category::where('status', '=', 1)->get();
        $categoriesToSelect = [];
        foreach ($allCategories as $key => $category) {
            $categoriesToSelect[$category->id] = $category->getDataFromColumn('name');
        }


        $videos = Media::where('type', 'upload')->where('model_id', null)->pluck('file_name', 'id');

        if (count($videos) == 0) {
            $videos = ['' => 'No videos available'];
        }

        return view('backend.courses.create', compact('videos', 'teachersToSelect', 'categoriesToSelect', 'courses', 'learned', 'academies','learned_ar'));
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCoursesRequest $request)
    {
        if (!Gate::allows('course_create')) {
            return abort(401);
        }
        $request = $this->saveFiles($request);
        $slug = str_slug($request->title);

        $slug_lesson = Course::where('slug', '=', $slug)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }
       
        $seats = 0;
        if ($request->offlineData) {
            foreach (json_decode($request->offlineData) as $key => $item) {
                $item = json_decode(json_encode($item), true);
                $seats += $item['seats-' . $key];
            }
        }
        $course = Course::create($request->except(['offlineData','duration']));
        $course->slug = $slug;
        $course->optional_courses = $request->opt_courses ? json_encode($request->opt_courses) : null;
        $course->mandatory_courses = $request->mand_courses ? json_encode($request->mand_courses) : null;
        $course->learned = $request->learned ? json_encode($request->learned) : null;
        $course->date = $request->offlineData ? json_encode($request->offlineData) : null;
        $course->seats = $seats;
        $course->save();

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Course::class;
            $model_id = $course->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $course->title . ' - video';
            $media = $course->mediavideo;
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
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->user_id = auth()->user()->id;
                $media->duration = $request->duration;
                $media->save();
            }

            if ($request->media_type == 'upload') {

                if ($request->video_file) {
                    $media = Media::findOrFail($request->video_file)->first();

                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->save();
                } else {
                    redirect()->back()->withFlashDanger('Please select a video from the list');
                }

            }
        }


        if ((int)$request->price == 0) {
            $course->price = NULL;
            $course->save();
        }


        $teachers = (auth()->user()->isAdmin() || auth()->user()->hasRole('academy')) ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $course->teachers()->sync($teachers);

        return redirect()->route('admin.courses.edit', ['course' => $course->id])->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Show the form for editing Course.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!Gate::allows('course_edit')) {
            return abort(401);
        }


       

        $allAcademies = User::role('academy')->with('academy')->get();
        $academies = [];
        foreach ($allAcademies as $academy) {
            $academies[$academy->id] = $academy->full_name;
        }
        $teachersToSelect = [];

        $allTeachers = User::role('teacher')->select('ar_first_name', 'ar_last_name', 'first_name', 'last_name', 'id')->get();

        foreach ($allTeachers as $key => $teachers) {
            $teachersToSelect[$teachers->id] = $teachers->full_name;
        }
        $allCategories = Category::where('status', '=', 1)->get();
        $categoriesToSelect = [];
        foreach ($allCategories as $key => $category) {
            $categoriesToSelect[$category->id] = $category->getDataFromColumn('name');
        }
        $allCourses = Course::pluck('title', 'id');

        $course = Course::findOrFail($id);


        $date = $course->date ? json_decode($course->date , true ) : null;

        $date = json_decode($date,true);
        //dd($date) ;
        //dd(json_decode($date,true));
    
        $opt_courses = $course->optional_courses ? json_decode($course->optional_courses) : null;

        $mand_courses = $course->mandatory_courses ? json_decode($course->mandatory_courses) : null;



        // $allLearned = $course->pluck('learned', 'id');
        $learned = $course->learned ? json_decode($course->learned) : null;
        $prevLearned = [];
        if ($learned != null) {
            foreach ($learned as $key => $value) {
                $prevLearned[$value] = $value;
            }
        }

        $timeline = CourseTimeline::where('course_id', $id)->get();
        // dd($timeline->isEmpty());
        if (!$timeline->isEmpty()) {
            foreach ($timeline as $item) {
                $content[] = $item->model_type::where('id', '=', $item->model_id)->get();

            }
            $chapterContent =[];
            foreach ($content as $key => $item) {

                foreach ($item as $j => $item) {
                    $chapterContent[] = $content[$key][$j];
                }

            }
        } else {
            $chapterContent = [];
            $timeline = [];
        }

        $videos = Media::where('type', 'upload')->where('model_id', null)->orWhere('model_id', $id)->pluck('file_name', 'id');
        $notSelectedVideos = Media::where('type', 'upload')->where('model_id', null)->pluck('file_name', 'id');
        if (count($videos) == 0 || count($notSelectedVideos) == 0) {
            $videos = ['' => 'No videos available'];
            $notSelectedVideos = ['' => 'No videos available'];
        }
//        dd($videos);
        return view('backend.courses.edit', compact('notSelectedVideos','chapterContent', 'videos', 'timeline', 'course', 'teachersToSelect', 'categoriesToSelect', 'course', 'opt_courses', 'mand_courses', 'allCourses', 'prevLearned', 'learned','academies','date'));


    }

    /**
     * Update Course in storage.
     *
     * @param \App\Http\Requests\UpdateCoursesRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCoursesRequest $request, $id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }
        $course = Course::findOrFail($id);

        $slug = str_slug($request->title);

        $slug_lesson = Course::where('slug', '=', $slug)->where('id', '!=', $course->id)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }
        $request = $this->saveFiles($request);
        //Saving  videos
        if ($request->media_type != "" || $request->media_type != null) {
            //            if ($course->mediavideo) {
            //                $course->mediavideo->delete();
            //            }
            $model_type = Course::class;
            $model_id = $course->id;
            $size = 0;
            $media = '';
            $url = 'default';
            $video_id = '';
            $name = $course->title . ' - video';
            $media = $course->mediavideo;
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
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->user_id = auth()->user()->id;
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
                    $media = Media::where('id', $request->video_file)->first();
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->save();
                } else {
                    redirect()->back()->withFlashDanger('Please select a video from the list');
                }

            }
        }

        $course->update($request->all());
        $course->optional_courses = $request->opt_courses ? json_encode($request->opt_courses) : Null;
        $course->mandatory_courses = $request->mand_courses ? json_encode($request->mand_courses) : Null;
        $course->learned = $request->learn ? json_encode($request->learn) : Null;
        $course->learned_ar = $request->learn ? json_encode($request->learn) : Null;
        //dd($request->offlineData);
        $course->date = $request->offlineData ? json_encode($request->offlineData) : Null;

        if ($request->opt_courses && $request->mand_courses) {
            if (count($request->opt_courses) != 0 || count($request->mand_courses) != 0 || count($request->learned) > 0) {
                $course->optional_courses = json_encode($request->opt_courses);
                $course->mandatory_courses = json_encode($request->mand_courses);
                $course->learned = json_encode($request->learned);
                $course->learned_ar = json_encode($request->learned_ar);
                $course->save();
            }
        }
        if (($request->slug == "") || $request->slug == null) {
            $course->slug = str_slug($request->title);
            $course->save();
        }
        if ((int)$request->price == 0) {
            $course->price = NULL;
            $course->save();
        }
        $seats = 0;
        if ($request->offlineData) {
            foreach (json_decode($request->offlineData) as $key => $item) {
                $item = json_decode(json_encode($item), true);
                $seats += $item['seats-' . $key];
            }
            $course->seats = $seats;
            $course->save();
        }
        $teachers = (auth()->user()->isAdmin() || auth()->user()->hasRole('academy')) ? array_filter((array)$request->input('teachers')) : [auth()->user()->id];
        $course->teachers()->sync($teachers);

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Course.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('course_view')) {
            return abort(401);
        }
        $teachers = User::get()->pluck('name', 'id');
        $lessons = \App\Models\Lesson::where('course_id', $id)->get();
        $tests = \App\Models\Test::where('course_id', $id)->get();

        $course = Course::findOrFail($id);
        $opt_courses = $course->optional_courses ? json_decode($course->optional_courses) : null;
        $mand_courses = $course->mandatory_courses ? json_decode($course->mandatory_courses) : null;
        $learn = $course->learned ? json_decode($course->learned) : null;
        $learn_ar = $course->learned_ar? json_decode($course->learned_ar) : null;

        if ($course->learned == 'null') {
            $learn = [];
        }
        if ($course->learned_ar == 'null') {
            $learn_ar = [];
        }


        $courseTimeline = $course->courseTimeline()->orderBy('sequence', 'asc')->get();

        return view('backend.courses.show', compact('course', 'lessons', 'tests', 'courseTimeline', 'opt_courses', 'mand_courses', 'learn','learn_ar'));
    }


    /**
     * Remove Course from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }


        $course = Course::findOrFail($id);
        if ($course->students->count() >= 1) {
            return redirect()->route('admin.courses.index')->withFlashDanger(trans('alerts.backend.general.delete_warning'));
        } else {
            $course->delete();
        }


        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Course at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Course::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Course from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->restore();

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Course from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->forceDelete();

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Permanently save Sequence from storage.
     *
     * @param Request
     */
    public function saveSequence(Request $request)
    {

    
//    dd($request->all());
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }
       
        foreach ($request->list as $item) {
            // dd($item);

            $courseTimeline = CourseTimeline::where('id',$item['id'])->first();
            $courseTimeline->sequence = $item['sequence'];
            $courseTimeline->save();
        }

        return 'success';
    }


    /**
     * Publish / Unpublish courses
     *
     * @param Request
     */
    public function publish($id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        $course = Course::findOrFail($id);
        if ($course->published == 1) {
            $course->published = 0;
        } else {
            $course->published = 1;
        }
        $course->save();

        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    public function courseContent($course_id)
    {

        $timeline = CourseTimeline::where('course_id', $course_id)->get();
        foreach ($timeline as $item) {
            $content[] = $item->model_type::where('id', '=', $item->model_id)->get();

        }


        foreach ($content as $key => $item) {
            foreach ($item as $j => $item) {
                $chapterContent[] = $content[$key][$j];
            }

        }

        // $course = Course::findOrFail($course_id);
        // $courseTimeline = $course->courseTimeline()->orderBy('sequence', 'asc')->get();
        // $chapters = $courseTimeline->where('model_type','chapter');

        // $chaptersOfCourse = Chapter::where('id',$chapters->model_id);


        return view('backend.courses.courseContent', compact('chapterContent', 'timeline'));


    }
}
