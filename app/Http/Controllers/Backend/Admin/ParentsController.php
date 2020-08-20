<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParentsController extends Controller
{

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(401);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param \App\Http\Requests\StoreTeachersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->parentEmail) {
            $parent = User::where('email', $request->parentEmail)->first();
            if ($parent) {

                if ($parent->hasRole('parent')) {
                    auth()->user()->parents()->attach($parent, ['status' => 0]);
                    return redirect()->route('admin.account')->withFlashSuccess(trans('alerts.backend.parent.invited'));
                } else {
                    return redirect()->back()->withFlashDanger(trans('alerts.backend.parent.not_parent'));
                }
            } else {
                return redirect()->back()->withFlashDanger(trans('alerts.backend.parent.not_found'));
            }
        }

    }


    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(401);
    }


    /**
     * Remove Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $parent = User::findOrFail($request->id);
        auth()->user()->parents()->detach($parent);
        return trans('alerts.backend.parent.deleted');
    }


}
