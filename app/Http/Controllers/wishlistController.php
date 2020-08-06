<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
class wishlistController extends Controller
{
    public function index(Request $request){

        $courses = auth()->user()->courses_active;
        // $courses = Course::with('students')->get();
        // $courses = Course::get();
        // $wishlists= ($courses->students->where('user_id', auth()->id()));

        return view('wishlist',compact('courses'));

     }

    //  public function remove(Request $request)
    //  {
    //     if (!Gate::allows('course_delete')) {
    //         return abort(401);
    //     }
    //     $course = Course::findOrFail($id);
    //     if ($course->students->count() >= 1) {
    //         return redirect()->route('admin.courses.index')->withFlashDanger(trans('alerts.backend.general.delete_warning'));
    //     } else {
    //         $course->delete();
    //     }

    //      return redirect(route('wishlist'));
    //  }
}
