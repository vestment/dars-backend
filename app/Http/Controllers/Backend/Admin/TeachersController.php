<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTeachersRequest;
use App\Http\Requests\Admin\UpdateTeachersRequest;
use App\Models\Auth\User;
use App\Models\TeacherProfile;
use App\academy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

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
            $teachers = TeacherProfile::onlyTrashed()->ofAcademy()->get();
        } else {
            $teachers = TeacherProfile::ofAcademy()->get();
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
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $teachers = "";


        if (request('show_deleted') == 1) {

            $teachers = User::ofAcademy()->role('teacher')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $teachers = User::ofAcademy()->role('teacher')->orderBy('created_at', 'desc')->get();
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
        $academies = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 5);
        })->get()->pluck('name', 'id');

        return view('backend.teachers.create', compact('academies'));
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
//        $request = $this->saveFiles($request);
// dd($request->all());

        $teacher = User::create($request->all());
        $teacher->confirmed = 1;
        $teacher->active = isset($request->active) ? 1 : 0;
        if (request()->type == "individual") {
            $academy_id = 0;
        } else {
            $academy_id = request()->academy_id;
        }

        $payment_details = [
            'bank_name' => request()->payment_method == 'bank' ? request()->bank_name : '',
            'ifsc_code' => request()->payment_method == 'bank' ? request()->ifsc_code : '',
            'account_number' => request()->payment_method == 'bank' ? request()->account_number : '',
            'account_name' => request()->payment_method == 'bank' ? request()->account_name : '',
            'paypal_email' => request()->payment_method == 'paypal' ? request()->paypal_email : '',
        ];
        $data = [
            'user_id' => $teacher->id,
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
            'payment_method' => request()->payment_method,
            'payment_details' => json_encode($payment_details),
            'description' => request()->description,
            'type' => request()->type,
            'percentage' => request()->percentage,
            'title' => request()->title,
            'academy_id' => $academy_id,


        ];
        TeacherProfile::create($data);
        if ($request->image) {
            $teacher->avatar_type = 'storage';
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = public_path() . '/storage/avatars';
            $file->move($path, $filename);
            $teacher->avatar_location = 'storage/avatars/' . $filename;
        }
        $teacher->save();
        $teacher->assignRole('teacher');
        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.created'));
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
//        $request = $this->saveFiles($request);

        $teacher = User::findOrFail($id);
        $teacher->update($request->except('email'));
        if ($request->has('image')) {
            $teacher->avatar_type = 'storage';
            $teacher->avatar_location = $request->image->store('/avatars', 'public');
        }
        $teacher->active = isset($request->active) ? 1 : 0;
        $teacher->save();

        $payment_details = [
            'bank_name' => request()->payment_method == 'bank' ? request()->bank_name : '',
            'ifsc_code' => request()->payment_method == 'bank' ? request()->ifsc_code : '',
            'account_number' => request()->payment_method == 'bank' ? request()->account_number : '',
            'account_name' => request()->payment_method == 'bank' ? request()->account_name : '',
            'paypal_email' => request()->payment_method == 'paypal' ? request()->paypal_email : '',
        ];
        $data = [
            // 'user_id'           => $user->id,
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
            'payment_method' => request()->payment_method,
            'payment_details' => json_encode($payment_details),
            'description' => request()->description,
            'title' => request()->title,

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
        $teacher = User::findOrFail($id);

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

        if ($teacher->courses->count() > 0) {
            return redirect()->route('admin.teachers.index')->withFlashDanger(trans('alerts.backend.general.teacher_delete_warning'));
        } else {
            $teacher->delete();
        }

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
