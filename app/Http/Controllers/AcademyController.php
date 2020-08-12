<?php

namespace App\Http\Controllers;

use App\academy;
use App\Models\Auth\User;
use App\Models\Category;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    private $path;

    public function __construct()
    {
        $path = 'frontend';
        if (session()->has('display_type')) {
            if (session('display_type') == 'rtl') {
                $path = 'frontend-rtl';
            } else {
                $path = 'frontend';
            }
        } else if (config('app.display_type') == 'rtl') {
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }

    public function show($id)
    {
        $academy = User::findOrFail($id);
        $academyData = academy::where('user_id',$id)->with('teachers')->get()[0];
        $categories = Category::get();
        return view('frontend.academy.show', compact('academy','academyData','categories'));
    }
}
