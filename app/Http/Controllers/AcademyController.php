<?php

namespace App\Http\Controllers;

use App\academy;
use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'frontend';
    }

    public function show($id)
    {
        // Refactored
        $academy = User::whereHas('academy', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->with('academy')->first();
        // Teachers associated with academy
        $academyTeachersCollection= TeacherProfile::where('academy_id', $id);
        $academyTeachers = $academyTeachersCollection->with('teacher')->get();
        $academyTeachersIds = $academyTeachersCollection->pluck('user_id');
        // Courses associated with academy teachers
        $coursesCollection = Course::whereHas('teachers', function ($query) use ($academyTeachersIds) {
            $query->whereIn('user_id', $academyTeachersIds);
        })->with('category');
        $courses = $coursesCollection->get();
        // Categories associated with academy courses
        $categories = array_unique($coursesCollection->pluck('category_id')->toArray());
        $categories = Category::whereIn('id',$categories)->get();

        return view('frontend.academy.show', compact('academy', 'academyTeachers','categories','courses'));
    }
}
