<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use AppWishlist;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Wishlist;


class wishlistController extends Controller
{
    public function index(Request $request)
    {

        $courses = auth()->user()->courses_ac;

        return view('wishlist', compact('courses'));

    }

//    public function index()
//    {
//        $user = Auth::user();
//        $wishlists = Wishlist::where("user_id", "=", $user->id)->orderby('id', 'desc')->paginate(10);
//        return view('frontend.wishlist', compact('user', 'wishlists'));
//    }
//
//
//    public function store(Request $request)
//    {
//        //Validating title and body field
//        $this->validate($request, array(
//            'user_id' => 'required',
//            'product_id' => 'required',
//        ));
//
//        $wishlist = new Wishlist;
//
//        $wishlist->user_id = $request->user_id;
//        $wishlist->product_id = $request->product_id;
//
//
//        $wishlist->save();
//
//        return redirect()->back()->with('flash_message',
//            'Item, ' . $wishlist->product->title . ' Added to your wishlist.');
//    }


    public function remove(Request $request)
    {


        // dd($request->course);
        $course_id = $request->course;
        $user_id = auth()->user()->id;
        $wishlist_id = auth()->user()->courses_ac->where('id', $course_id)->first();
        //    dd( $wishlist_id);
        $wishlist_id->update([
            "pivot_wishlist" => 0
        ]);


        // $courses = auth()->user()->courses_active;
        // $courses->updateExistingPivot('wishlist' , 0);
        return redirect(route('wishlist'));
    }
}
