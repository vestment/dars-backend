<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTeachersRequest;
use App\Http\Requests\Admin\UpdateTeachersRequest;
use App\Models\Auth\User;
use App\Models\TeacherProfile;
use App\academy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
class TeachersController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('show_deleted') == 1) {
            $teachers = TeacherProfile::onlyTrashed()->ofAcademy()->with('teacher')->get();
        } else {
            $teachers = TeacherProfile::ofAcademy()->with('teacher')->get();
        }
        return view('backend.teachers.index', compact('teachers'));
    }

    // public function allAcademies()
    // {
    //     $academies = User::role('academy')->get();
    //     return view('backend.teachers.create', compact('academies'));


    // }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getacademyTeachers($academy_id)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $teachers = "";


        if (request('show_deleted') == 1) {
            $teachers = User::ofAcademy()->role('teacher')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $teachers = User::with('TeacherProfile')->ofAcademy()->role('teacher')->where('academy_id', $academy_id)->orderBy('created_at', 'desc')->get();
        }
        if (auth()->user()->isAdmin() || auth()->user()->hasRole('academy')) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }
        return DataTables::of($teachers)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.teachers', 'label' => 'teacher', 'value' => $q->id]);
                }

                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.teachers.show', ['teacher' => $q->id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.teachers.edit', ['teacher' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.teachers.destroy', ['teacher' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a class="btn btn-blue mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

                return $view;

            })
            ->editColumn('status', function ($q) {
                $html = html()->label(html()->checkbox('')->id($q->id)
                        ->checked(($q->active == 1) ? true : false)->class('switch-input')->attribute('data-id', $q->id)->value(($q->active == 1) ? 1 : 0) . '<span class="switch-label"></span><span class="switch-handle"></span>')->class('switch switch-lg switch-3d switch-primary');
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
            ->rawColumns(['actions', 'image', 'status'])
            ->make();

    }

    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $teachers = "";


        if (request('show_deleted') == 1) {

            $teachers = User::ofAcademy()->role('teacher')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $teachers = User::with('TeacherProfile')->ofAcademy()->role('teacher')->orderBy('created_at', 'desc')->get();

        }
        if (auth()->user()->isAdmin() || auth()->user()->hasRole('academy')) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }


        return DataTables::of($teachers)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.teachers', 'label' => 'teacher', 'value' => $q->id]);
                }

                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.teachers.show', ['teacher' => $q->id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.teachers.edit', ['teacher' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.teachers.destroy', ['teacher' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a class="btn btn-blue mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

                return $view;

            })
            ->editColumn('status', function ($q) {
                $html = html()->label(html()->checkbox('')->id($q->id)
                        ->checked(($q->active == 1) ? true : false)->class('switch-input')->attribute('data-id', $q->id)->value(($q->active == 1) ? 1 : 0) . '<span class="switch-label"></span><span class="switch-handle"></span>')->class('switch switch-lg switch-3d switch-primary');
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
            ->rawColumns(['actions', 'image', 'status'])
            ->make();
    }

    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       

        return view('backend.teachers.create');
        // return view('backend.teachers.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param \App\Http\Requests\StoreTeachersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeachersRequest $request)
    {
        // dd('here32') ; 
        $validator = Validator::make(Input::all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);
        if ($validator->passes()) {
            $request = $this->saveAvatar($request);

        $teacher = User::create($request->all());
        $teacher->confirmed = 1;
        $teacher->active = isset($request->active) ? 1 : 0;
      
        $teacher->save();
        $teacher->assignRole('teacher');
        
        $data = [
            'user_id' => $teacher->id,
            'description' => request()->description,
            'ar_description' => request()->ar_description,
            'percentage' => request()->percentage,
            'title' => request()->title,
            'ar_title' => request()->ar_title,
            
        ];
        TeacherProfile::create($data);
        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.created'));
        }
        return back()->with(['errors'=>$validator->errors()]);
    }


    /**
     * Show the form for editing Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        return view('backend.teachers.edit', compact('teacher'));
    }

    /**
     * Update Category in storage.
     *
     * @param \App\Http\Requests\UpdateTeachersRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeachersRequest $request, $id)
    {

        $request = $this->saveAvatar($request);
        $teacher = User::findOrFail($id);
        $teacher->update($request->except('email'));

        $teacher->active = isset($request->active) ? 1 : 0;
        $teacher->save();

       
        $data = [
            // 'user_id'           => $user->id,
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
           
            'description' => request()->description,
            'title' => request()->title,
            'ar_description' => request()->ar_description,
            'ar_title' => request()->ar_title,

        ];
        $teacher->teacherProfile->update($data);

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = User::role('teacher')->with('teacherProfile')->findOrFail($id);

        return view('backend.teachers.show', compact('teacher'));
    }


    /**
     * Remove Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $teacher = User::findOrFail($id);

        // if ($teacher->courses->count() > 0) {
        //     return redirect()->route('admin.teachers.index')->withFlashDanger(trans('alerts.backend.general.teacher_delete_warning'));
        // } else {
        $teacher->delete();
        // }

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Category at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->restore();

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {

        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->teacherProfile->delete();
        $teacher->forceDelete();

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }


    /**
     * Update teacher status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    public function updateStatus()
    {
        $teacher = User::find(request('id'));
        $teacher->active = $teacher->active == 1 ? 0 : 1;
        $teacher->save();
    }
}
