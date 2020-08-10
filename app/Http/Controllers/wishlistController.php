<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
class wishlistController extends Controller
{
    public function index(Request $request){

        $courses = auth()->user()->courses_active;
        // dd($courses);
        return view('wishlist',compact('courses'));

     }

     public function remove(Request $request)
     {


        // dd($request->course);
        $course_id = $request->course;
        $user_id = auth()->user()->id;
        $wishlist_id = auth()->user()->courses_active->where('id',$course_id)->first();
       
        $wishlist_id->update([
            "wishlist"=> 0
        ]);


        // $courses = auth()->user()->courses_active;
        // $courses->updateExistingPivot('wishlist' , 0);
        return redirect(route('wishlist'));
     }
}
