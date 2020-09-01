<?php

namespace App\Http\Controllers;

use App\Helpers\Auth\Auth;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\Test;
use App\Models\TestsResult;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Note;

class LessonsController extends Controller
{


    public function show($course_id, $lesson_slug)
    {
        $test_result = "";
        $completed_lessons = "";

        $lesson = Lesson::where('slug', $lesson_slug)->where('course_id', $course_id)->where('published', '=', 1)->first();
         if ($lesson == "") {
                $lesson = Test::where('slug', $lesson_slug)->where('course_id', $course_id)->with('courseTimeline')->where('published', '=', 1)->firstOrFail();
                $lesson->full_text = $lesson->description;

                $test_result = NULL;
                if ($lesson) {
                    $test_result = TestsResult::where('test_id', $lesson->id)
                        ->where('user_id', \Auth::id())
                        ->first();
                }
            }
        if($lesson){
        $purchased_course = $lesson->course->students()->where('user_id', \Auth::id())->count() > 0;
        
        if ($purchased_course) {
            $chapters = Chapter::where('course_id', $course_id)->where('published', '=', 1)->get();


         

            if ((int)config('lesson_timer') == 0) {
                if ($lesson->chapterStudents()->where('user_id', \Auth::id())->count() == 0) {
                    $lesson->chapterStudents()->create([
                        'model_type' => get_class($lesson),
                        'model_id' => $lesson->id,
                        'user_id' => auth()->user()->id,
                        'course_id' => $lesson->course->id
                    ]);
                }
            }

            $course_lessons = $lesson->course->lessons->pluck('id')->toArray();
            $course_tests = ($lesson->course->tests) ? $lesson->course->tests->pluck('id')->toArray() : [];
            $course_lessons = array_merge($course_lessons, $course_tests);
<<<<<<< HEAD
=======
//dd($lesson->courseTimeline()->get());
>>>>>>> 69885dba0176f709bc8bbfec561fe3be7133e71b
            $previous_lesson = $lesson->course->courseTimeline()
                ->where('sequence', '<', $lesson->courseTimeline->sequence)
                ->whereIn('model_id', $course_lessons)
                ->where('model_type', Lesson::class)
                ->orderBy('sequence', 'desc')
                ->first();

            $next_lesson = $lesson->course->courseTimeline()
                ->whereIn('model_id', $course_lessons)
                ->where('sequence', '>', $lesson->courseTimeline->sequence)
                ->where('model_type', Lesson::class)
                ->orderBy('sequence', 'asc')
                ->first();

            $lessons = $lesson->course->courseTimeline()
                ->whereIn('model_id', $course_lessons)
                ->where('model_type', Lesson::class)
                ->orderby('sequence', 'asc')
                ->get();


            $test_exists = FALSE;

            if (get_class($lesson) == 'App\Models\Test') {
                $test_exists = TRUE;
            }

            $completed_lessons = \Auth::user()->chapters()
                ->where('course_id', $lesson->course->id)
                ->get()
                ->pluck('model_id')
                ->toArray();
            $start_time = time();
            if (auth()->user()->current_test()->first()) {
                $pivot = auth()->user()->current_test()->first()->pivot->where('test_id', $lesson->id)->first();
                // dd($pivot);
                if ($pivot) {
                    $start_time = $pivot->start_time;
                }

            }
        }

        $notes = Note::where(['lesson_id' => $lesson->id,'user_id' => \Auth::id() ])->get();
        
            return view('frontend.courses.lesson', compact('chapters', 'lesson', 'previous_lesson', 'next_lesson', 'test_result',
                'purchased_course', 'test_exists', 'lessons', 'completed_lessons', 'start_time','notes'));
        } else {
            return abort(403);
//            return redirect()->back()->withFlashDanger(__('labels.frontend.cart.complete_your_purchases'));
        
        }
    }

    public function editNotes(Request $request){

        $notes_modal = Note::where(['id' => $request->id])->first();
        return $notes_modal;


    }

    public function saveNotes(Request $request)
    {
        $lesson = lesson::where('slug', $request->lesson_slug)->firstOrFail();

        $notes = Note::create([
            'lesson_id' => $lesson->id,
            'user_id' => \Auth::id(),
            'contentText' => $request->contentText,
        ]);
        
      
        return redirect()->back();



    }
    public function updateNotes(Request $request)
    {
        $notes_modal = Note::where('id', $request->note_id)->firstOrFail();

       
        $notes_modal->contentText = $request->contentText;
        $notes_modal->save();
        
      
        return redirect()->back();



    }
    public function showNotes(Request $request)
    {
        $lesson = lesson::where('slug', $request->lesson_slug)->firstOrFail();

        $notes = Note::where(['lesson_id' => $lesson->id,'user_id' => \Auth::id() ])->get();
        
      
        return view('frontend.courses.lesson', compact('notes') );


    }

    public function availablityUpdate(Request $request)
    {
        // return $request;
        $test = Test::where('slug', $request->lesson_slug)->firstOrFail();
        $test->available = 0;
        $test->save();
        return $test;
    }

    public function startTimeUpdate(Request $request)
    {
        return $request;
//        $test = Test::where('slug', $request->lesson_slug)->firstOrFail();
        $check_prev_entry = auth()->user()->current_test()->where('test_id', $request->id)->first();
        if (!$check_prev_entry) {
            auth()->user()->current_test()->attach($request->id, ['start_time' => time()]);
        }

    }

    public function test($lesson_slug, Request $request)
    {
        $test = Test::where('slug', $lesson_slug)->firstOrFail();
        $answers = [];
        $test_score = 0;
        if (!$request->get('questions')) {

            return back()->with(['flash_warning' => 'No options selected']);
        }
        foreach ($request->get('questions') as $question_id => $answer_id) {
            $question = Question::find($question_id);
            $correct = QuestionsOption::where('question_id', $question_id)
                    ->where('id', $answer_id)
                    ->where('correct', 1)->count() > 0;
            $answers[] = [
                'question_id' => $question_id,
                'option_id' => $answer_id,
                'correct' => $correct
            ];
            if ($correct) {
                if ($question->score) {
                    $test_score += $question->score;
                }
            }
            /*
             * Save the answer
             * Check if it is correct and then add points
             * Save all test result and show the points
             */
        }
        $test_result = TestsResult::create([
            'test_id' => $test->id,
            'user_id' => \Auth::id(),
            'test_result' => $test_score,
        ]);
        $test_result->answers()->createMany($answers);


        if ($test->chapterStudents()->where('user_id', \Auth::id())->get()->count() == 0) {
            $test->chapterStudents()->create([
                'model_type' => $test->model_type,
                'model_id' => $test->id,
                'user_id' => auth()->user()->id,
                'course_id' => $test->course->id
            ]);
        }


        return back()->with(['message' => 'Test score: ' . $test_score, 'result' => $test_result]);
    }
    

    public function retest(Request $request)
    {
        $test = TestsResult::where('id', '=', $request->result_id)
            ->where('user_id', '=', auth()->user()->id)
            ->first();
        $test->delete();
        return back();
    }

    public function videoProgress(Request $request)
    {
        $user = auth()->user();
        $video = Media::findOrFail($request->video);
        $video_progress = VideoProgress::where('user_id', '=', $user->id)
            ->where('media_id', '=', $video->id)->first() ?: new VideoProgress();
        $video_progress->media_id = $video->id;
        $video_progress->user_id = $user->id;
        $video_progress->duration = $video_progress->duration ?: round($request->duration, 2);
        $video_progress->progress = round($request->progress, 2);
        if ($video_progress->duration - $video_progress->progress < 5) {
            $video_progress->progress = $video_progress->duration;
            $video_progress->complete = 1;
        }
        $video_progress->save();
        return $video_progress->progress;
    }


    public function courseProgress(Request $request)
    {
        if (\Auth::check()) {
            $lesson = Lesson::find($request->model_id);
            if ($lesson != null) {
                if ($lesson->chapterStudents()->where('user_id', \Auth::id())->get()->count() == 0) {
                    $lesson->chapterStudents()->create([
                        'model_type' => $request->model_type,
                        'model_id' => $request->model_id,
                        'user_id' => auth()->user()->id,
                        'course_id' => $lesson->course->id
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

}
