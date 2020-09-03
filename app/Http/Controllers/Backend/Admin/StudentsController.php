<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Auth\User;
use App\Models\Course;
use App\Models\TestsResult;
use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\DataTables;

class StudentsController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentsIds = auth()->user()->students->pluck('id');
        // dd($studentsIds);
        if (request('show_deleted') == 1) {

            $students = User::role('student')->whereIn('id', $studentsIds)->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $students = User::role('student')->whereIn('id', $studentsIds)->orderBy('created_at', 'desc')->get();;

        }
        return view('backend.students.index', compact('students'));
    }


    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.students.create');
        // return view('backend.teachers.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param \App\Http\Requests\StoreTeachersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);
        if ($validator->passes()) {
            $request = $this->saveAvatar($request);
            $student = User::create($request->all());
            $student->assignRole('student');
            $student->confirmed = 1;
            $student->active = isset($request->active) ? 1 : 0;
            $student->save();
            $student->parents()->sync(auth()->user());
            return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.created'));
        }
        return back()->with(['errors'=>$validator->errors()]);
    }

    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $students = "";
        $studentsIds = auth()->user()->students->pluck('id');

        if (request('show_deleted') == 1) {

            $students = User::role('student')->whereIn('id', $studentsIds)->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $students = User::role('student')->whereIn('id', $studentsIds)->with('parents')->orderBy('created_at', 'desc')->get();
        }
        if (auth()->user()->isAdmin() || auth()->user()->hasRole('parent')) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }
        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {

                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.students', 'label' => 'students', 'value' => $q->id]);
                }

                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.students.show', ['student' => $q->id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.students.edit', ['student' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.students.destroy', ['student' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                foreach ($q->parents as $parent) {
                    if ($parent->pivot->student_id == $q->id && $parent->pivot->parent_id == auth()->user()->id) {
                        if ($parent->pivot->status == 0) {
                            $accept = view('backend.datatable.action-invitation')
                                ->with(['route' => route('admin.students.accept', ['student' => $q->id]), 'order' => 'accept'])
                                ->render();
                            $view .= $accept;
                            $decline = view('backend.datatable.action-invitation')
                                ->with(['route' => route('admin.students.decline', ['student' => $q->id]), 'order' => 'decline'])
                                ->render();
                            $view .= $decline;
                        }
                    }

                }
                // $view .= '<a class="btn btn-blue mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

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
     * Show the form for editing Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('backend.students.edit', compact('student'));
    }

    public function acceptInvite($id)
    {
        $student = User::role('student')->with('parents')->findOrFail($id);
        $pivo = $student->parents()->where('parent_id',auth()->user()->id)->where('student_id',$id)->first();
        $pivo->pivot->status = 1;
        $pivo->pivot->save();
        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.parent.accepted_invite'));
    }
    public function declineInvite($id)
    {
        $student = User::role('student')->with('parents')->findOrFail($id);
        $pivo = $student->parents()->where('parent_id',auth()->user()->id)->where('student_id',$id)->first();
        $pivo->pivot->delete();
        return redirect()->route('admin.students.index')->withFlashDanger(trans('alerts.backend.parent.declined_invite'));
    }
    /**
     * Update Category in storage.
     *
     * @param \App\Http\Requests\ $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request = $this->saveAvatar($request);
        $student = User::findOrFail($id);
        $student->update($request->except('email'));

        $student->active = isset($request->active) ? 1 : 0;
        $student->save();
        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = User::with('courses_active')->findOrFail($id);
        $purchased_courses = Course::whereHas('students', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->get();
        // dd($purchased_courses);

        return view('backend.students.show', compact('student', 'purchased_courses'));
    }


    /**
     * Remove Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $student = User::findOrFail($id);

        if ($student->courses->count() > 0) {
            return redirect()->route('admin.students.index')->withFlashDanger(trans('alerts.backend.general.teacher_delete_warning'));
        } else {
            $student->delete();
        }
//        auth()->user()->students()->detach($student, ['status' => 0]);

        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
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
//                auth()->user()->students()->detach($entry, ['status' => 0]);
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
        $student = User::onlyTrashed()->findOrFail($id);
        $student->restore();
        auth()->user()->students()->sync($student, ['status' => 1]);
        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {

        $student = User::onlyTrashed()->findOrFail($id);
        $student->forceDelete();
//        auth()->user()->students()->detach($student, ['status' => 0]);
        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }


    /**
     * Update teacher status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    public function updateStatus()
    {
        $student = User::find(request('id'));
        $student->active = $student->active == 1 ? 0 : 1;
        $student->save();
    }
    public function getChapters(Request $request)
    {
        $chapters = Chapter::where('course_id',$request->id)->with('test')->get();
        $content = [];
        foreach ($chapters as $key => $value) {
        $arrToPush =[];
        $arrToPush['chapter'] = $value;
        $arrToPush['chapter']['test'] = $value->test;
        $arrToPush['chapter']['test']->title = $value->test->getDataFromColumn('title');
        $arrToPush['chapter']->title = $value->getDataFromColumn('title');
       if (auth()->user()->hasRole('parent')) {
        $testResults = TestsResult::where('test_id',$value->test->id)->whereIn('user_id',auth()->user()->students()->pluck('id'))->get();
       } else {
        $testResults = TestsResult::where('test_id',$value->test->id)->where('user_id',auth()->user()->id)->get();
       }
        $arrToPush['chapter']['test']['results'] = $testResults;

        array_push($content,$arrToPush);

        }
       return $content;

    }
}
