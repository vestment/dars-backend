<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Auth\User;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use getID3;

class VideoBankController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Media::all();
        return view('backend.videos.index', compact('videos'));
    }

    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $videos = "";

        $videos = Media::where('type', 'upload');
        if (auth()->user()->hasRole('teacher')) {
            $videos = $videos->where('user_id',auth()->user()->id);
        }
        if (auth()->user()->hasRole('academy')) {
            $teachers = auth()->user()->academy->with('teachers')->first()->teachers()->pluck('user_id');
            $teachers[auth()->user()->id] = auth()->user()->id;
//            dd($teachers);
            $videos = $videos->whereIn('user_id',$teachers);
        }
        $videos = $videos->with('uploader')->orderBy('created_at', 'desc')->get();
//        dd($videos);
        if (auth()->user()->isAdmin() || auth()->user()->hasRole('teacher') || auth()->user()->hasRole('academy')) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }
        return DataTables::of($videos)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {

                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.students', 'label' => 'students', 'value' => $q->id]);
                }

//                if ($has_view) {
//                    $view = view('backend.datatable.action-view')
//                        ->with(['route' => route('admin.video-bank.show', ['student' => $q->id])])->render();
//                }

//                if ($has_edit) {
//                    $edit = view('backend.datatable.action-edit')
//                        ->with(['route' => route('admin.video-bank.edit', ['student' => $q->id])])
//                        ->render();
//                    $view .= $edit;
//                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.video-bank.destroy', ['student' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                // $view .= '<a class="btn btn-blue mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

                return $view;

            })->editColumn('name', function ($q) {
                $course_name = explode('-', $q->name)[0];

                return $course_name;
            })->editColumn('model_type', function ($q) {
                $course_name = explode('-', $q->name)[0];

                return $course_name;
            })
            ->editColumn('url', function ($q) {
                $url = '<a href="'.asset($q->url).'">'.$q->file_name.' </a>';
                if ($q->uploader) {
                    $url .= '<p>Uploaded by '.$q->uploader->full_name.'</p> ';
                }
                return $url;
            })
            ->rawColumns(['actions','url'])
            ->make();
    }

    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.videos.create');
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
        if ($request->file('video_file')) {
            ini_set('memory_limit','-1');
            foreach ($request->file('video_file') as $file) {
                $allowedType = ['video/mp4', 'video/avi', 'video/mkv'];
                if (!in_array($file->getClientMimeType(), $allowedType)) {
                    return redirect()
                        ->back()
                        ->withFlashDanger('Uploaded file must be a video');
                } else {

                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads';
                    $filename = str_replace(' ', '-', $filename);
                    $file->move($path, $filename);
                    $url = 'storage/uploads/' . $filename;
                    $getID3 = new getID3();
                    $video_file = $getID3->analyze($url);
                    // Get the duration in string, e.g.: 4:37 (minutes:seconds)
                    $duration_string = strtotime($video_file['playtime_string']);
                    $duration_string = date('H:i:s',$duration_string);
                    // Get the duration in seconds, e.g.: 277 (seconds)
                    $duration_seconds = $video_file['playtime_seconds'];
                    $media = new Media();
                    $media->model_type = '';
                    $media->model_id = null;
                    $media->name = 'Un selected - video';
                    $media->url = $url;
                    $media->user_id = auth()->user()->id;
                    $media->type = 'upload';
                    $media->file_name = $filename;
                    $media->size = $size;
                    $media->duration = $duration_string;
                    $media->save();
                }
            }
//            return redirect()->route('admin.video-bank.index')->withFlashSuccess(trans('alerts.backend.general.created'));
            return response()->json(['status' => 'success', 'message' => __('alerts.backend.general.created')]);
        } else {
            return response()->json(['status' => 'error', 'message' => __('alerts.backend.general.error')]);

        }

    }


    /**
     * Show the form for editing Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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


    }


    /**
     * Display Category.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    /**
     * Remove Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return redirect()->route('admin.video-bank.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Category at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        if ($request->input('ids')) {
            $entries = Media::whereIn('id', $request->input('ids'))->get();

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

    }

    /**
     * Permanently delete Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {

    }


    /**
     * Update teacher status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    public function updateStatus()
    {

    }
}
