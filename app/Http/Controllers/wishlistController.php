<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Cart;

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
    public function addToCart(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {
            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $product->price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers
                    ]);
        }
        // Session::flash('success', trans('labels.frontend.cart.product_added'));
        $course_id = $request->course;
        $wishlist = auth()->user()->wishList->where('id', $course_id)->first();
        dd('wishlist');
        $wishlist->pivot->delete();
       
       
        return back();
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
