<?php


namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTeachersRequest;
use App\Http\Requests\Admin\UpdateTeachersRequest;
use App\Models\Auth\User;
use App\academy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AcademyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::role('academy')->get();
        return view('backend.academies.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $academies = "";


        if (request('show_deleted') == 1) {

            $academies = User::role('academy')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $academies = User::role('academy')->orderBy('created_at', 'desc')->get();
        }

        if (auth()->user()->isAdmin()) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }


        return DataTables::of($academies)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.academies', 'label' => 'academy', 'value' => $q->id]);
                }

                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.academies.show', ['academy' => $q->id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.academies.edit', ['academy' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.academies.destroy', ['academy' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a class="btn btn-blue mb-1" href="' . route('admin.teachers.index', ['academy_id' => $q->id]) . '">' . trans('labels.backend.teachers.title') . '</a>';

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

    public function create()
    {
        return view('backend.academies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request = $this->saveFiles($request);

        $academy = User::create($request->all());
        $academy->confirmed = 1;
        if ($request->file('image')) {
            $academy->avatar_type = 'storage';
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = public_path() . '/storage/uploads/academies/' . $academy->id;
            $file->move($path, $filename);
//            $academyLogo = asset('storage/uploads/academies/' . $filename);
            $academy->avatar_location = 'storage/uploads/academies/' . $academy->id . '/' . $filename;
        }
        $academy->active = isset($request->active) ? 1 : 0;
        $academy->save();
        $academy->assignRole('academy');
        $galleryFiles = request()->gallery;
        if ($galleryFiles) {
            $galleryImages = [];
            foreach ($galleryFiles as $galleryImage) {
                $filename = time() . '-' . $galleryImage->getClientOriginalName();
                $path = public_path() . '/storage/uploads/academies/' . $academy->id . '/gallery';
                $galleryImage->move($path, $filename);
                array_push($galleryImages, asset('storage/uploads/academies/' . $academy->id . '/gallery/' . $filename));
            }
            $gallery = json_encode($galleryImages);
        } else {
            $gallery = null;
        }

        $payment_details = [
            'bank_name' => request()->payment_method == 'bank' ? request()->bank_name : '',
            'ifsc_code' => request()->payment_method == 'bank' ? request()->ifsc_code : '',
            'account_number' => request()->payment_method == 'bank' ? request()->account_number : '',
            'account_name' => request()->payment_method == 'bank' ? request()->account_name : '',
            'paypal_email' => request()->payment_method == 'paypal' ? request()->paypal_email : '',
        ];
        $data = [
            'user_id' => $academy->id,
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
            'payment_method' => request()->payment_method,
            'payment_details' => json_encode($payment_details),
            'description' => request()->description,
            'ar_description' => request()->ar_description,
'adress' => request()->address,
            'logo' => $academy->avatar_location,
            'percentage' => request()->percentage,
            'gallery' => $gallery
        ];
//        dd($data);
        academy::create($data);


        return redirect()->route('admin.academies.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $academy = User::findOrFail($id);


        return view('backend.academies.show', compact('academy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $academy = User::findOrFail($id);
        $academyData = academy::where('user_id', $id)->get()[0];

        return view('backend.academies.edit', compact('academy', 'academyData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $academy = User::findOrFail($id);
        $academy->update($request->except('email'));
        $academyData = academy::where('user_id', $id)->get()[0];
        if ($request->file('image')) {
            $academy->avatar_type = 'storage';
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $path = public_path() . '/storage/avatars';
            $file->move($path, $filename);
//            $academyLogo = asset('storage/uploads/academies/' . $filename);
            $academy->avatar_location = 'storage/avatars/' . $filename;
        }
        $academy->active = isset($request->active) ? 1 : 0;
        $academy->save();
        if (request()->gallery) {
            $newGalleryFiles = request()->gallery; // New Images
            $galleryImages = $academyData->gallery ? json_decode($academyData->gallery) : []; // Old Gallery images
            foreach ($newGalleryFiles as $newGalleryImage) {
                $filename = time() . '-' . $newGalleryImage->getClientOriginalName();
                $path = public_path() . '/storage/uploads/academies/' . $academy->id . '/gallery';
                $newGalleryImage->move($path, $filename);
                array_push($galleryImages, asset('storage/uploads/academies/' . $academy->id . '/gallery/' . $filename));
            }
        } else {
            $galleryImages = $academyData->gallery;
        }

        $payment_details = [
            'bank_name' => request()->payment_method == 'bank' ? request()->bank_name : '',
            'ifsc_code' => request()->payment_method == 'bank' ? request()->ifsc_code : '',
            'account_number' => request()->payment_method == 'bank' ? request()->account_number : '',
            'account_name' => request()->payment_method == 'bank' ? request()->account_name : '',
            'paypal_email' => request()->payment_method == 'paypal' ? request()->paypal_email : '',
        ];
        $data = [
            'facebook_link' => request()->facebook_link,
            'twitter_link' => request()->twitter_link,
            'linkedin_link' => request()->linkedin_link,
            'payment_method' => request()->payment_method,
            'payment_details' => json_encode($payment_details),
            'description' => request()->description,
            'ar_description' => request()->ar_description,
            'gallery' => json_encode($galleryImages)

        ];
        $academyData->update($data);


        return redirect()->route('admin.academies.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = User::findOrFail($id);


        // return redirect()->route('admin.teachers.index')->withFlashDanger(trans('alerts.backend.general.teacher_delete_warning'));

        $teacher->delete();


        return redirect()->route('admin.academies.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
