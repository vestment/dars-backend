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
        })->with('category')->where('published',1);
        $courses = $coursesCollection->get();
        // Categories associated with academy courses
        $categories = array_unique($coursesCollection->pluck('category_id')->toArray());
        $categories = Category::whereIn('id',$categories)->get();

        return view('frontend.academy.show', compact('academy', 'academyTeachers','categories','courses'));
    }
    public function show911()
    {
        $id=29;
        // Refactored
        $academy = academy::where('user_id', $id)->with('user')->first();

        // Teachers associated with academy
        $academyTeachersCollection= TeacherProfile::where('academy_id', $id);
        $academyTeachers = $academyTeachersCollection->with('teacher')->get();
        $academyTeachersIds = $academyTeachersCollection->pluck('user_id');
        // Courses associated with academy teachers
        $coursesCollection = Course::whereHas('teachers', function ($query) use ($academyTeachersIds) {
            $query->whereIn('user_id', $academyTeachersIds);
        })->with('category')->where('published',1);
        $courses = $coursesCollection->get();
        // Categories associated with academy courses
        $categories = Category::where('name','911')->first();
        $courses_911 = Course::where('category_id', $categories->id )->with('category')->get();
        // Merge the courses into 1 variable
        $courses = $courses->merge($courses_911);
        return view('frontend.academy.911', compact('academy', 'academyTeachers','courses'));
    }
}
