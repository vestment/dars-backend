<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Course;
use Illuminate\Http\Request;


class wishlistController extends Controller
{
    public function index(Request $request)
    {
        $courses = auth()->user()->wishList;
        return view('frontend.wishlist', compact('courses'));

    }


//
    public function store(Request $request)
    {
        $courseData = Course::findOrFail($request->course_id);
        $wishlist = auth()->user()->wishList->where('id', $request->course_id)->first();
        if ($wishlist) {
            $wishlist->pivot->wishlist = 1;
            $wishlist->pivot->save();
        } else {
            auth()->user()->wishList()->attach($request->course_id, ['wishlist' => 1]);
        }
        if (app()->getLocale() == 'ar') {
            $msg = 'تم أضافة  ' . $courseData->getDataFromColumn('title') . ' ألي قائمتك المفضلة';
        } else {
            $msg = 'Item, ' . $courseData->getDataFromColumn('title') . ' Added to your wishlist.';
        }
        return redirect()->route('wishlist.index')->with('success_message',$msg);
    }


    public function remove(Request $request)
    {
        $course_id = $request->course;
        $wishlist = auth()->user()->wishList->where('id', $course_id)->first();
//        $wishlist->pivot->wishlist = 0;
//        $wishlist->pivot->save();
        $wishlist->pivot->delete();
        return redirect()->back()->with(['message'=>'Item removed']);
    }
}
