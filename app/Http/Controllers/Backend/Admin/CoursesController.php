<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Courses;
use App\Models\Test;
use App\Models\Country;
use App\Models\EduSystem;
use App\Models\EduStage;
use App\Models\Semester;
use App\Models\EduStageSemester;
use App\Models\CourseEduStatgeSem;
use Illuminate\Support\Arr;
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
//            ->addColumn('lessons', function ($q) {
//                $lesson = '<a href="' . route('admin.lessons.create', ['course_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.courses.courseContent', ['course_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
//                return $lesson;
//            })
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
        $countriesToSelect = [];
        $countries = Country::get();
        foreach ($countries as $key => $country) {
            $countriesToSelect[$country->id] = $country->getDataFromColumn('name');
        }
       
        $videos = Media::where('type', 'upload')->where('model_id', null)->pluck('file_name', 'id');

        if (count($videos) == 0) {
            $videos = ['' => 'No videos available'];
        }
        // $EduSystem = [];

        return view('backend.courses.create', compact('countriesToSelect','videos', 'teachersToSelect', 'categoriesToSelect', 'courses', 'learned', 'academies', 'learned_ar'));
    }
public function getCountryedusys(Request $request ){

    $EduSystem = EduSystem::where('country_id', $request->id)->with('country')->get();
    return $EduSystem;
    
}

public function getEduStatgesOfES (Request $request){
    $eduStatges = EduStage::wherein('edu_system_id',$request->ids)->get();
    return $eduStatges;
}

public function getSemestersOfES(Request $request){

    $semesterIDs = EduStageSemester::wherein('edu_stage_id',$request->ids)->pluck('semester_id');
    $semesters = Semester::wherein('id',$semesterIDs)->get();
    return $semesters;


}


    /**
     * Store a newly created Course in storage.
     *
     * @param \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCoursesRequest $request)
    {
        // dd($request->all());
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
        // dd($request->all());



        foreach ($request->eduStatge as $i => $statge) {
            foreach($request->semesters as $j => $semester) {
               $ids[] = EduStageSemester::where('edu_stage_id',$request->eduStatge [$i])->where('semester_id',$request->semesters [$j])->pluck('id');
            }

        }
            $statgeSemestersIDS = Arr::collapse($ids);
      
        $course = Course::create($request->except(['offlineData', 'duration', 'learned', 'learned_ar', 'opt_courses', 'mand_courses', 'seats']));
        $course->slug = $slug;
        $course->optional_courses = $request->opt_courses ? json_encode($request->opt_courses) : null;
        $course->mandatory_courses = $request->mand_courses ? json_encode($request->mand_courses) : null;
        $course->learned = $request->learned ? json_encode($request->learned) : json_encode([]);
        $course->learned_ar = $request->learn_ar ? json_encode($request->learn_ar) : json_encode([]);
        $course->date = $request->offlineData ? json_encode($request->offlineData) : null;
        $course->seats = $seats;
        $course->save();
foreach($statgeSemestersIDS as $i=>$semID){

 $courseSemester = new CourseEduStatgeSem();
 $courseSemester->course_id = $course->id;
 $courseSemester->edu_statge_sem_id = $statgeSemestersIDS[$i];
 $courseSemester->save();
}


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
            $course->free = 1;
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

        $coursesRaw = Course::all();
        $allLearned = [];
        foreach ($coursesRaw as $course) {
            if ($course->learned && $course->learned != 'null') {
                foreach (json_decode($course->learned) as $learnItem) {
                    $allLearned[$learnItem] = $learnItem;
                }
            }
        }
        $allLearned_ar = [];
        foreach ($coursesRaw as $course) {
            if ($course->learned_ar && $course->learned_ar != 'null') {
                foreach (json_decode($course->learned_ar) as $learnItem) {
                    $allLearned_ar[$learnItem] = $learnItem;
                }
            }
        }
        $allAcademies = User::role('academy')->with('academy')->get();
        $academies = [];
        foreach ($allAcademies as $academy) {
            $academies[$academy->id] = $academy->full_name;
        }
        $teachersToSelect = [];
        $status = true;
        $selectedTeachers = $course->teachers->pluck('id')->toArray();

        

        $allTeachers = User::role('teacher')->select('ar_first_name', 'ar_last_name', 'first_name', 'last_name', 'id')->get();
        
        foreach ($allTeachers as $key => $teacher) {

            $teachersToSelect[$teacher->id] = $teacher->full_name;
           
            if( in_array( $teacher->id, $selectedTeachers)){
                
            }
        }
      
        $allCategories = Category::where('status', '=', 1)->get();
        $categoriesToSelect = [];
        foreach ($allCategories as $key => $category) {
            $categoriesToSelect[$category->id] = $category->getDataFromColumn('name');
        }
        $allCourses = Course::pluck('title', 'id');

        $course = Course::findOrFail($id);
        $date = $course->date ? json_decode($course->date, true) : null;

        $date = json_decode($date, true);
        //dd($date) ;
        //dd(json_decode($date,true));

        $opt_courses = $course->optional_courses ? json_decode($course->optional_courses) : null;

        $mand_courses = $course->mandatory_courses ? json_decode($course->mandatory_courses) : null;
        $learned = $course->learned ? json_decode($course->learned) : null;
        $prevLearned = [];
        if ($learned != null) {
            foreach ($learned as $key => $value) {
                $prevLearned[$value] = $value;
            }
        }
        $learned_ar = $course->learned_ar ? json_decode($course->learned_ar) : null;
        $prevLearned_ar = [];
        if ($learned_ar != null) {
            foreach ($learned_ar as $key => $value) {
                $prevLearned_ar[$value] = $value;
            }
        }

        $courseEduSemIDs = CourseEduStatgeSem::where('course_id',$id)->pluck('edu_statge_sem_id');

        $semesterIds = EduStageSemester::wherein('id',$courseEduSemIDs)->pluck('semester_id');

        // $semesters = Semester::wherein('id',$semesterIds)->get();

        $eduStatgeIDs = EduStageSemester::wherein('id',$courseEduSemIDs)->pluck('edu_stage_id');

        $eduStatges = EduStage::wherein('id',$eduStatgeIDs)->get();

        $allEducationStatges = EduStage::get();

        $eduSystemIds = $eduStatges->pluck('edu_system_id');
        $allEduSystems = EduSystem::select('id','en_name')->get();
        $allEduSystems =$allEduSystems;
        $allEduSystems2 = [];
        foreach($allEduSystems as $index => $EduSystem){
            $allEduSystems2[]=[$allEduSystems[$index]->id,$allEduSystems[$index]->en_name];

        }
        $allEduSystems->toJson();
        // return $allEduSystems;

        $edusystemdata = EduSystem::wherein('id',$eduSystemIds)->get();
        $country_id =  $edusystemdata->pluck('country_id');
        $country = Country::where('id',$country_id)->get();
        $countriesToSelect = [];
        $countries = Country::get();
        foreach ($countries as $key => $country) {
            $countriesToSelect[$country->id] = $country->getDataFromColumn('name');
        }
        $courseTimeLine = CourseTimeline::where('course_id', $id)->with(['model'])->orderBy('sequence', 'asc')->get();
        $courseSequence = [];
        foreach ($courseTimeLine as $key => $item) {
            if ($item->model_type == Chapter::class) {
                $courseChapter[$item->id] = $item;
                $courseChapter[$item->id]['childs'] = CourseTimeline::where('course_id', $id)
                    ->where('chapter_id', $item->model_id)
                    ->whereIn('model_type', [Lesson::class,Test::class])
                    ->orderBy('sequence', 'asc')->get();
                array_push($courseSequence, $courseChapter[$item->id]);
            }
        }
        $videos = Media::where('type', 'upload')->where('model_id', null)->orWhere('model_id', $id)->pluck('file_name', 'id');
        $notSelectedVideos = Media::where('type', 'upload')->where('model_id', null)->pluck('file_name', 'id');
        if (count($videos) == 0 || count($notSelectedVideos) == 0) {
            $videos = ['' => 'No videos available'];
            $notSelectedVideos = ['' => 'No videos available'];
        }
        return view('backend.courses.edit', compact('eduSystemIds','allEducationStatges','courseEduSemIDs','allEduSystems','allEduSystems2','eduStatgeIDs','countriesToSelect','country_id','notSelectedVideos', 'courseSequence', 'videos', 'teachersToSelect', 'categoriesToSelect', 'course', 'opt_courses', 'mand_courses', 'allCourses', 'prevLearned', 'prevLearned_ar', 'allLearned', 'allLearned_ar', 'academies', 'date'));


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

        $slug_exits = Course::where('slug', '=', $slug)->where('id', '!=', $course->id)->first();
        if ($slug_exits) {
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
                    $oldMedia = Media::where('model_id', $id)->where('model_type', $model_type)->first();
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

        if ($request->offlineData) {
            $seats = 0;
            foreach (json_decode($request->offlineData, true) as $key => $item) {
                foreach ($item as $SingleDatekey => $value) {
                    if (substr($SingleDatekey, 0, 4) == 'seat') {
                        $seats += $value;
                    }
                }
            }
            $course->seats = $seats ? $seats : $course->seats;
        }
        $course->update($request->except(['offlineData', 'duration', 'learned', 'learned_ar', 'opt_courses', 'mand_courses', 'seats']));
        if ($request->offlineData) {
            $course->date = $request->offlineData ? json_encode($request->offlineData) : Null;
        }
        if ($request->opt_courses && count($request->opt_courses) > 0) {
            $course->optional_courses = $request->opt_courses ? json_encode($request->opt_courses) : json_encode([]);
        }
        if ($request->mand_courses && count($request->mand_courses) > 0) {
            $course->mandatory_courses = $request->mand_courses ? json_encode($request->mand_courses) : json_encode([]);
        }
        if ($request->learn_ar && count($request->learn_ar) > 0) {
            $course->learned_ar = $request->learn_ar ? json_encode($request->learn_ar) : json_encode([]);
        }
        if ($request->learn && count($request->learn) > 0) {
            $course->learned = $request->learn ? json_encode($request->learn) : json_encode([]);
        }
        if (($request->slug == "") || $request->slug == null) {
            $course->slug = str_slug($request->title);
        }
        if ((int)$request->price == 0) {
            $course->price = NULL;
            $course->free = 1;
        }
        $course->save();
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
        $learn_ar = $course->learned_ar ? json_decode($course->learned_ar) : null;

        if ($course->learned == 'null') {
            $learn = [];
        }
        if ($course->learned_ar == 'null') {
            $learn_ar = [];
        }


        $courseTimeline = $course->courseTimeline()->orderBy('sequence', 'asc')->get();

        return view('backend.courses.show', compact('course', 'lessons', 'tests', 'courseTimeline', 'opt_courses', 'mand_courses', 'learn', 'learn_ar'));
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
            foreach ($course->students as $student) {
                $student->pivot->delete();
            }
//            return redirect()->route('admin.courses.index')->withFlashDanger(trans('alerts.backend.general.delete_warning'));
        }
        $course->delete();


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

            $courseTimeline = CourseTimeline::findorfail($item['id']);
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
        $courseTimeLine = CourseTimeline::where('course_id', $course_id)->with(['model'])->orderBy('sequence', 'asc')->get();
        $courseSequence = [];
        foreach ($courseTimeLine as $key => $item) {
            if ($item->model_type == Chapter::class) {
                $courseChapter[$item->id]['data'] = $item->model;
                $courseChapter[$item->id]['childs'] = CourseTimeline::where('course_id', $course_id)
                    ->where('chapter_id', $item->model_id)
                    ->where('model_type', Lesson::class)
                    ->orWhere('model_type', Test::class)
                    ->orderBy('sequence', 'asc')->get();
                array_push($courseSequence, $courseChapter[$item->id]);
            }

        }

        return view('backend.courses.courseContent', compact('courseSequence'));


    }

    public function must_finish($id)
    {

        $timeline = CourseTimeline::where('model_type', 'App\Models\Test')->where('model_id', $id)->first();
        $timeline->must_finish = 1;
        $timeline->save();
        return redirect()->route('admin.courses.edit', ['course' => $timeline->course_id])->withFlashSuccess(trans('alerts.backend.general.created'));
    }
}
