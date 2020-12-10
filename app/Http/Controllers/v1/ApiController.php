<?php

namespace App\Http\Controllers\v1;

use App\Helpers\General\EarningHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Mail\Frontend\Contact\SendContact;
use App\Mail\OfflineOrderMail;
use App\Models\Auth\Traits\SendUserPasswordReset;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Chapter;
use App\Models\Config;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\EduStage;
use App\Models\EduSystem;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Order;
use App\Models\Page;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\Reason;
use App\Models\Review;
use App\Models\Sponsor;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Tax;
use App\Models\Test;
use App\Models\Testimonial;
use App\Models\TestsResult;
use App\Models\TestsResultsAnswer;
use App\Models\VideoProgress;
use App\Models\EduStageSemester;
use App\Models\CourseEduStatgeSem;
use App\Models\studentData;
use App\Models\Year;
use App\Models\StudentCart;
use  App\Models\Auth\SocialAccount;
use App\Models\SMS;
use App\Models\Package;



use App\Note;
use App\Repositories\Frontend\Auth\UserRepository;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Carbon\Carbon;
use DevDojo\Chatter\Events\ChatterAfterNewResponse;
use DevDojo\Chatter\Events\ChatterBeforeNewDiscussion;
use DevDojo\Chatter\Events\ChatterBeforeNewResponse;
use DevDojo\Chatter\Mail\ChatterDiscussionUpdated;
use Harimayco\Menu\Models\MenuItems;
use http\Client\Response;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Cart;
use DevDojo\Chatter\Events\ChatterAfterNewDiscussion;
use Event;
use DevDojo\Chatter\Models\Models;
use DevDojo\Chatter\Helpers\ChatterHelper as Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repositories\SemesterRepositoryInterface;
use Purifier;
use Messenger;
use Newsletter;


class ApiController extends Controller
{
    use FileUploadTrait;
    use SendsPasswordResetEmails;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }



    public function __invoke(Request $request)
    {
        $this->validateEmail($request);
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['status' => 'success', 'message' => 'Reset link sent to your email.'], 201)
            : response()->json(['status' => 'failure', 'message' => 'Unable to send reset link. No Email found.'], 401);
    }

    public function index()
    {
        return view('frontend.layouts.modals.login');
    }

    public function indexrtl()
    {
        return view('frontend-rtl.layouts.modals.login');
    }

    public function registerIndex()
    {
        return view('frontend.layouts.modals.register');
    }

    /**
     * Get the Signup Form
     *
     * @return [json] config object
     */
    public function signupForm()
    {
        $fields = [];
        if (config('registration_fields') != NULL) {
            $fields = json_decode(config('registration_fields'), true);
        }
        //        if (config('access.captcha.registration') > 0) {
        //            $fields[] = ['name' => 'g-recaptcha-response', 'type' => 'captcha'];
        //        }
        return response()->json(['status' => 'success', 'fields' => $fields]);
    }
    public function checkPhoneConfirmationCode(Request $request, $user_id)
    {
        $code =  $request->code;
        $user = User::findOrFail($user_id);
        $status = "wrong code";
        if ($user->phone_confirmed) {
            return response()->json(['status' => 'already confirmed']);
        }
        if ($user->phone_confirmation_code == $code) {
            $user->phone_confirmed = true;
            $user->save();
            $status = 'user phone ' . $user->phone . ' has been confirmed';
        }
        return response()->json(['status' => $status]);
    }

    public function sendCodeToUserPhone(Request $request, $user_id)
    {
        // $value = $request->session()->get('last_send_time', 'default');

        // // dd(Carbon::parse() ) ; 
        // dd(($value->addHour(6))) ; 
        // // dd(Carbon::now()->diffInHours($value->addHour(2))) ; 
        // if(Carbon::parse() > ($value->addHour(5)) ){
        //     return response()->json(['status' => "good"]);
        // }else{
        //     return response()->json(['status' => "you will have three attempts after one hour of the last attempt"]);
        // }

        // dd($value) ; 

        $user = User::findOrFail($user_id);
        $code =  $user->phone_confirmation_code;

        $status = "";
        if ($user->phone_confirmed) {
            $status = 'user phone ' . $user->phone  . ' already confirmed';
        } else {

            if ($request->session()->has('send_attempts')) {
                $attempts = $request->session()->get('send_attempts');
                if ($attempts >= 3) {
                    $value = $request->session()->get('last_send_time');
                    if (Carbon::parse() > ($value->addHour(1))) {
                        $request->session()->put('send_attempts', 1);
                        $request->session()->put('last_send_time', Carbon::now());
                        $status =  SMS::send("confirmation code : " . $code, $user->phone, "I Friends", "9u89oJ9a0u", "Dars");
                        return response()->json(['status' => $status]);
                    } else {
                        return response()->json(['status' => "you will have three attempts after one hour of the last attempt"]);
                    }
                }
                $request->session()->put('send_attempts', $attempts + 1);
                $request->session()->put('last_send_time', Carbon::now());
                $status =  SMS::send("confirmation code : " . $code, $user->phone, "I Friends", "9u89oJ9a0u", "Dars");
            } else {
                $request->session()->put('send_attempts', 1);
                $request->session()->put('last_send_time', Carbon::now());
                $status =  SMS::send("confirmation code : " . $code, $user->phone, "I Friends", "9u89oJ9a0u", "Dars");
            }
        }
        return response()->json(['status' => $status]);
    }

    public function getCategoryCourses(Request $request)
    {
        $statgeSemIds = EduStageSemester::where(['edu_stage_id' => $request->statge_id, 'semester_id' => $request->semester_id])->value('id');


        $coursesIds = CourseEduStatgeSem::where('edu_statge_sem_id', $statgeSemIds)->get()->pluck('course_id');
        $courses = [];
        foreach ($coursesIds as $id) {
            $course =  Course::where(['id' => $id, 'category_id' => $request->category_id])->with('category', 'teachers', 'year')->first();
            if ($course) {
                array_push($courses, $course);
            }
        }
        return response()->json(['status' => 'success', 'categoryCourses' =>  $courses]);
    }
    public function SocialLogin(Request $request)
    {

        if ($request->email) {
            $user = User::where('email', $request->email)->first();
        }
        if ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
        }
        if ($request->provider_id) {

            $user = SocialAccount::where('provider_id', $request->provider_id)->first();
        }

        if (!$user) {
            $user = User::where('email', $request->created_email)->first();
            $user->phone = $request->phone;
            // $user = new User([
            //     'first_name' => $request->first_name,
            //     'last_name' => $request->last_name,
            //     'email' => $request->email,
            //     'phone' => $request->phone,

            // ]);
            $user->save();
            $userData = new studentData([
                'user_id' => $user->id,
                'country_id' => $request->country_id,
                'edu_system_id' => $request->eduSystemId,
                'edu_stage_id' => $request->eduStatgeId,

            ]);
            $userData->save();

            // $socialAccount = new SocialAccount([
            //     'user_id' => $user->id,
            //     'provider' => $request->provider,
            //     'provider_id' => $request->provider_id,
            //     'avater' => $request->avater,
            //     'token' => $request->token

            // ]);
            // $socialAccount->save();
        }


        $userData  = studentData::where('user_id', $user->id)->first();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();
        return response()->json([
            'user' => $user,
            'userData' => $userData,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ]);
    }
    public function vectorylink()
    {
        // dd('here') ; 
        // $quota = SMS::checkCredit('I Friends' , '9u89oJ9a0u') ; 
        $status =  SMS::send("welcome To Dars", "01025130834dd", "I Friends", "9u89oJ9a0u", "Dars");
        return response()->json(['status' => $status]);
    }



    public function signup(Request $request)
    {
        $validation = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            //            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required', new CaptchaRule()] : ''),
        ], [
            //            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);

        if (!$validation) {
            return response()->json(['errors' => $validation->errors()]);
        }
        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if (isset($request->phone)) {
            $code = "";
            for ($i = 0; $i < 6; $i++) {
                $code = $code . rand(0, 9);
            }
            $user->phone_confirmation_code = $code;
            // dd($user->phone_confirmation_code) ; 
        }

        $user->dob = isset($request->dob) ? $request->dob : NULL;
        $user->phone = isset($request->phone) ? $request->phone : NULL;
        $user->gender = isset($request->gender) ? $request->gender : NULL;
        $user->address = isset($request->address) ? $request->address : NULL;
        $user->city = isset($request->city) ? $request->city : NULL;
        $user->pincode = isset($request->pincode) ? $request->pincode : NULL;
        $user->state = isset($request->state) ? $request->state : NULL;
        $user->country = isset($request->country) ? $request->country : NULL;
        $user->save();

        $userData = new studentData([
            'user_id' => $user->id,
            'country_id' => $request->country_id,
            'edu_system_id' => $request->eduSystemId,
            'edu_stage_id' => $request->eduStatgeId,

        ]);
        $userData->save();

        $userForRole = User::find($user->id);
        $userForRole->confirmed = 1;
        $userForRole->save();
        $userForRole->assignRole('student');
        $user->save();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully created user!',
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'userData' => $userData
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();

        $userData  = studentData::where('user_id', $user->id)->first();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'user' => $user,
            'userData' => $userData,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }



    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Get the App Config
     *
     * @return [json] config object
     */
    public function getConfig(Request $request)
    {
        $data = ['font_color', 'contact_data', 'counter', 'total_students', 'total_courses', 'total_teachers', 'logo_b_image', 'logo_w_image', 'logo_white_image', 'contact_data', 'footer_data', 'app.locale', 'app.display_type', 'app.currency', 'app.name', 'app.url', 'access.captcha.registration', 'paypal.active', 'payment_offline_active'];
        $json_arr = [];
        $config = Config::whereIn('key', $data)->select('key', 'value')->get();
        foreach ($config as $data) {
            if ((array_first(explode('_', $data->key)) == 'logo') || (array_first(explode('_', $data->key)) == 'favicon')) {
                $data->value = asset('storage/logos/' . $data->value);
            }
            $json_arr[$data->key] = (is_null(json_decode($data->value, true))) ? $data->value : json_decode($data->value, true);
        }
        return response()->json(['status' => 'success', 'data' => $json_arr]);
    }


    /**
     * Get  courses
     *
     * @return [json] course object
     */
    public function getCourses(Request $request)
    {
        $types = ['popular', 'trending', 'featured'];
        $type = ($request->type) ? $request->type : null;
        if ($type != null) {
            if (in_array($type, $types)) {
                $courses = Course::where('published', '=', 1)
                    ->where($type, '=', 1)
                    ->paginate(10);
            } else {
                return response()->json(['status' => 'failure', 'message' => 'Invalid Request']);
            }
        } else {
            $courses = Course::where('published', '=', 1)
                ->paginate(10);
        }

        return response()->json(['status' => 'success', 'type' => $type, 'result' => $courses]);
    }

    public function coursesOfStatge(Request $request)
    {

        $semesters = EduStageSemester::where('edu_stage_id', $request->statge_id)->get();

        $statgeSemIds = EduStageSemester::where('edu_stage_id', $request->statge_id)->with('courses')->get();

        $newCourses = [];
        foreach ($statgeSemIds as $key => $course) {
            foreach ($statgeSemIds[$key]->courses as $index => $element) {

                if ($statgeSemIds[$key]->courses[$index]['created_at'] >= Carbon::today()->subDays(3)) {
                    $newCourses[] = $statgeSemIds[$key]->courses[$index];
                }
            }
        }
        $semesterNames = [];
        foreach ($semesters as $i => $sem) {
            $semesterNames[] = Semester::where('id', $semesters[$i]->semester_id)->first();
        }
        return response()->json(['status' => 'success', 'semesters' => $statgeSemIds, 'newCourses' => $newCourses, 'semesterNames' => $semesterNames]);
    }

    /**
     * Search Basic
     *
     * @return [json] Course / Bundle / Blog object
     */
    public function search(Request $request)
    {
        $result = NULL;
        if ($request->type) {

            if ($request->type == 1) {
                $result = Course::where('title', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                    ->where('published', '=', 1)
                    ->with('teachers')
                    ->paginate(10);
            } elseif ($request->type == 2) {
                $result = Bundle::where('title', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                    ->where('published', '=', 1)
                    ->with('user')
                    ->paginate(10);
            } elseif ($request->type == 3) {
                $result = Blog::where('title', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('content', 'LIKE', '%' . $request->q . '%')
                    ->with('author')
                    ->paginate(10);
            }
        }
        $type = $request->type;
        $q = $request->q;
        return response()->json(['status' => 'success', 'q' => $q, 'type' => $type, 'result' => $result]);
    }

    /**
     * Latest News / Blog
     *
     * @return [json] Blog object
     */
    public function getLatestNews(Request $request)
    {
        $blog = Blog::orderBy('created_at', 'desc')
            ->select('id', 'category_id', 'user_id', 'title', 'slug', 'content', 'image')
            ->paginate(10);
        return response()->json(['status' => 'success', 'result' => $blog]);
    }


    /**
     * Get Latest Testimonials
     *
     * @return [json] Testimonial object
     */
    public function getTestimonials(Request $request)
    {
        $testimonials = Testimonial::where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json(['status' => 'success', 'result' => $testimonials]);
    }

    /**
     * Get Teachers
     *
     * @return [json] Teacher object
     */
    public function getTeachers(Request $request)
    {
        $teachers = User::role('teacher')->with('teacherProfile')->paginate(10);
        if ($teachers == null) {
            return response()->json(['status' => 'failure', 'result' => null]);
        }
        return response()->json(['status' => 'success', 'result' => $teachers]);
    }

    /**
     * Get Single Teacher
     *
     * @return [json] Teacher object
     */
    public function getSingleTeacher(Request $request)
    {
        $teacher = User::role('teacher')->find($request->teacher_id);
        if ($teacher == null) {
            return response()->json(['status' => 'failure', 'result' => null]);
        }
        $courses = $teacher->courses->take(5);
        $bundles = $teacher->bundles->take(5);
        $profile = $teacher->teacherProfile->first();
        return response()->json(['status' => 'success', 'result' => ['teacher' => $teacher, 'courses' => $courses, 'bundles' => $bundles, 'profile' => $profile]]);
    }
    public function StatgeCourses($statge_id)
    {

        $semesters = EduStageSemester::where('edu_stage_id', $statge_id)->get();


        $statgeSemIds = EduStageSemester::where('edu_stage_id', $statge_id)->get();



        $newCourses = [];
        foreach ($statgeSemIds as $key => $course) {
            foreach ($statgeSemIds[$key]->courses as $index => $element) {

                if ($statgeSemIds[$key]->courses[$index]['created_at'] >= Carbon::today()->subDays(3)) {
                    $newCourses[] = $statgeSemIds[$key]->courses[$index];
                }
            }
        }
        $semesterNames = [];
        foreach ($semesters as $i => $sem) {
            $semesterNames[] = Semester::where('id', $semesters[$i]->semester_id)->first();
        }
        return response()->json(['status' => 'success', 'semesters' => $statgeSemIds, 'newCourses' => $newCourses, 'semesterNames' => $semesterNames]);
    }
    public function getSingleUser(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => 'failure', 'result' => null]);
        }
        return response()->json(['status' => 'success', 'result' => ['user' => $user]]);
    }

    /**
     * Get Teacher Courses
     *
     * @return [json] Teacher Courses object
     */
    public function getTeacherCourses(Request $request)
    {
        $teacher = User::role('teacher')->find($request->teacher_id);
        if ($teacher == null) {
            return response()->json(['status' => 'failure', 'result' => null]);
        }
        $courses = $teacher->courses()->get();
        return response()->json(['status' => 'success', 'result' => ['teacher' => $teacher, 'courses' => $courses]]);
    }

    /**
     * Get Teacher Bundles
     *
     * @return [json] Teacher Bundles object
     */
    public function getTeacherBundles(Request $request)
    {
        $teacher = User::role('teacher')->find($request->teacher_id);
        if ($teacher == null) {
            return response()->json(['status' => 'failure', 'result' => null]);
        }
        $bundles = $teacher->bundles()->paginate(10);
        return response()->json(['status' => 'success', 'result' => ['teacher' => $teacher, 'bundles' => $bundles]]);
    }

    /**
     * Get FAQs
     *
     * @return [json] FAQs object
     */
    public function getFaqs()
    {

        $faqs = Faq::whereHas('category')
            ->where('status', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['status' => 'success', 'result' => $faqs]);
    }

    /**
     * Get WHy Us Reasons
     *
     * @return [json] Reason object
     */
    public function getWhyUs()
    {
        $reasons = Reason::where('status', '=', 1)->paginate(10);
        return response()->json(['status' => 'success', 'result' => $reasons]);
    }

    /**
     * Get Sponsors
     *
     * @return [json] Sponsors object
     */
    public function getSponsors()
    {

        $sponsors = Sponsor::where('status', '=', 1)->get();
        return response()->json(['status' => 'success', 'result' => $sponsors]);
    }

    /**
     * Save Contact Us Request
     *
     * @return [json] Success feedback
     */
    public function saveContactUs(Request $request)
    {
        $validation = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        if (!$validation) {
            return response()->json(['status' => 'failure', 'errors' => $validation->errors()]);
        }

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->number = $request->number;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->save();

        Mail::send(new SendContact($request));
        return response()->json(['status' => 'success']);
    }

    /**
     * Get Single Course
     *
     * @return [json] Success feedback
     */
    public function getSingleCourse(Request $request)
    {


        $continue_course = NULL;
        $course_timeline = NULL;
        $course = Course::where('id', $request->course_id)->withoutGlobalScope('filter')->with('teachers', 'category', 'chapters', 'year')->with('publishedLessons')->first();
        if ($course == null) {
            return response()->json(['status' => 'failure', 'result' => NULL]);
        }
        $course->learned = json_decode($course->learned);
        $course->learned_ar = json_decode($course->learned_ar);

        $singleCourse = Course::where('id', $request->course_id)->withoutGlobalScope('filter')->with('teachers', 'category', 'chapters')->with('publishedLessons', 'tests')->first();
        $Coursesss = $singleCourse->students()->get();

        $MyCourses = [];

        foreach ($Coursesss as $i => $courseee) {
            if ($Coursesss[$i]->pivot->user_id == auth('api')->user()->id) {

                $MyCourses[] = $Coursesss[$i]->pivot->course_id;
            }
            // if(in_array(auth('api')->user()->id , $myCourses[$i]->pivot->user_id))

        }
        // $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;


        $purchased_course = in_array($request->course_id, $MyCourses);
        $chapters = $course->chapters()->where('course_id', $course->id)->get();
        $chapter_lessons = Lesson::where('course_id', $course->id)->where('published', '=', 1);
        // $progress = $course->progress();
        $course_rating = 0;
        $total_ratings = 0;
        $completed_lessons = NULL;
        $is_reviewed = false;
        if (auth('api')->check() && $course->reviews()->where('user_id', '=', auth('api')->user()->id)->first()) {
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence', 'asc')->get();



        if (\Auth::check()) {
            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $continue_course = $course->courseTimeline()->orderby('sequence', 'asc')->whereNotIn('model_id', $completed_lessons)->first();
            if ($continue_course == NULL) {
                $continue_course = $course->courseTimeline()->orderby('sequence', 'asc')->first();
            }
        }

        if ($course->courseTimeline) {

            $timeline = $course->courseTimeline()->orderBy('sequence')->get();
            foreach ($timeline as $item) {
                $completed = false;
                if ($completed_lessons) {
                    if (in_array($item->model_id, $completed_lessons)) {
                        $completed = true;
                    }
                }
                $type = '';
                $description = "";
                if ($item->model_type == 'App\Models\Test') {
                    $type = 'test';
                    $description = $item->model->description;
                } else {
                    $description = $item->model->short_text;
                }
                $course_timeline[] = [
                    'title' => $item->model->title,
                    'type' => $type,
                    'id' => $item->model_id,
                    'description' => $description,
                    'completed' => $completed,
                ];
            }
        }
        $mediaVideo = (!$course->mediaVideo) ? null : $course->mediaVideo->toArray();
        if ($mediaVideo && $mediaVideo['type'] == 'embed') {
            preg_match('/src="([^"]+)"/', $mediaVideo['url'], $match);
            $url = $match[1];
            $mediaVideo['file_name'] = $url;
        }
        $result = [
            'course' => $course,
            'course_video' => $mediaVideo,
            'purchased_course' => $purchased_course,
            'course_rating' => $course_rating,
            'total_ratings' => $total_ratings,
            'is_reviewed' => $is_reviewed,
            // 'lessons' => $lessons,
            // 'course_timeline' => $course_timeline,
            'completed_lessons' => $completed_lessons,
            'continue_course' => $continue_course,
            // 'progress' =>$progress
            // 'chapters' => $chapters
            // 'is_certified' => $course->isUserCertified(),
            // 'course_process' => $course->progress()
        ];
        return response()->json(['status' => 'success', 'result' => $result]);
    }


    /**
     * Submit review
     *
     * @return [json] Success message
     */
    public function submitReview(Request $request)
    {
        $reviewable_id = $request->item_id;
        if ($request->type == 'course') {
            $reviewable_type = Course::class;
            $item = Course::find($request->item_id);
        } else {
            $reviewable_type = Bundle::class;
            $item = Bundle::find($request->item_id);
        }
        if ($item != null) {
            $review = new Review();
            $review->user_id = auth()->user()->id;
            $review->reviewable_id = $reviewable_id;
            $review->reviewable_type = $reviewable_type;
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failure']);
    }

    /**
     * Update Review
     *
     * @return [json] Success message
     */
    public function updateReview(Request $request)
    {
        $review = Review::where('id', '=', $request->review_id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review != null) {
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();

            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failure']);
    }

    /**
     * Get Lesson
     *
     * @return [json] Success message
     */
    public function getLesson(Request $request)
    {
        // dd(auth()->user());

        $lesson = Lesson::where('published', '=', 1)
            ->where('slug', $request->lesson)
            ->with(['chapterStudents'])
            ->first();

        if ($lesson != null) {
            if ($lesson->course && !in_array(auth()->user()->id, $lesson->course->students()->pluck('user_id')->toArray())) {
                return response()->json(['status' => 'failure', 'message' => 'You need to buy this course first']);
            }
            $course = Course::withoutGlobalScope('filter')->with('teachers')->findOrFail($lesson->course_id);
            $courseTimeLine = $lesson->course->courseTimeline()->with(['model'])->orderBy('sequence', 'asc')->get();
            $courseSequence = [];
            foreach ($courseTimeLine as $key => $item) {
                if ($item->model_type == Chapter::class) {
                    $courseChapter[$item->id]['data'] = $item->model;
                    $courseChapter[$item->id]['lessons'] = $lesson->course->courseTimeline()
                        ->with(['model', 'model.downloadableMedia', 'model.notes', 'model.mediaPDF', 'model.mediaAudio', 'model.mediaVideo'])
                        ->where('chapter_id', $item->model_id)
                        ->where('model_type', Lesson::class)
                        ->orderBy('sequence', 'asc')->get();
                    $courseChapter[$item->id]['test'] = $lesson->course->courseTimeline()
                        ->with(['model', 'model.testResult', 'model.questions'])
                        ->where('chapter_id', $item->model_id)
                        ->where('model_type', Test::class)
                        ->orderBy('sequence', 'asc')->first();
                    $prev_Chapter = $lesson->course->courseTimeline()->where('sequence', '<', $item->sequence)
                        ->where('model_type', Chapter::class)
                        ->where('course_id', $item->course_id)
                        ->orderBy('sequence', 'desc')->first();
                    $prevChapter = null;
                    if ($prev_Chapter) {
                        $prevChapter = Chapter::where('id', $prev_Chapter->model_id)->with(['test', 'test.testResult'])->first();
                    }
                    foreach ($courseChapter[$item->id]['lessons'] as $index => $chapterLesson) {
                        $chapterLesson['canView'] = true;
                        if ($index != 0) {
                            $prevLesson = $courseChapter[$item->id]['lessons'][$index - 1];
                            $LessonCompleted = auth()->user()->chapters()->where('model_id', $prevLesson->model_id)->first();
                            if (!$LessonCompleted) {
                                $chapterLesson['key'] = $index;
                                $chapterLesson['canView'] = false;
                            }
                        }
                        if ($prevChapter) {
                            $chapterLesson['key'] = $prev_Chapter->model_id;
                            $chapterTest = $prevChapter->test;
                            if ($chapterTest && count($chapterTest->testResult) > 0) {
                                if ($chapterTest->testResult[count($chapterTest->testResult) - 1]->test_result < $chapterTest->min_grade) {
                                    $chapterLesson['canView'] = false;
                                    $chapterLesson['key'] = $index . '- Failed';
                                }
                            } elseif ($chapterTest && count($chapterTest->testResult) == 0) {
                                $chapterLesson['canView'] = false;
                                $chapterLesson['key'] = $index . '- No result';
                            }
                        }
                    }
                    array_push($courseSequence, $courseChapter[$item->id]);
                }
            }
            $chapters = $course->chapters()->with(['test'])->get();
            $previous_lesson = $lesson->course->courseTimeline()->where('sequence', '<', $lesson->courseTimeline->sequence)
                ->where('model_type', Lesson::class)
                ->with('model')
                ->orderBy('sequence', 'desc')
                ->first();
            $next_lesson = $lesson->course->courseTimeline()->where('sequence', '>', $lesson->courseTimeline->sequence)
                ->where('model_type', Lesson::class)
                ->with('model')
                ->orderBy('sequence', 'asc')
                ->first();
            $is_certified = $lesson->course->isUserCertified();
            $course_progress = $lesson->course->progress();

            $downloadable_media = $lesson->downloadable_media;

            $mediaVideo = (!$lesson->mediaVideo) ? null : $lesson->mediaVideo->toArray();
            if ($mediaVideo && $mediaVideo['type'] == 'embed') {
                preg_match('/src="([^"]+)"/', $mediaVideo['url'], $match);
                $url = $match[1];
                $lesson->mediaVideo['file_name'] = $url;
                $lesson->mediaVideo['duration'] = $lesson->mediaVideo->durations;
            } elseif ($mediaVideo && $mediaVideo['type'] == 'upload') {
                // Stream Video if uploaded
                $lesson->mediaVideo['url'] = '' . route('videos.stream', ['encryptedId' => Crypt::encryptString($mediaVideo['id'])]) . '';
                $lesson->mediaVideo['duration'] = $lesson->mediaVideo->durations;
            }
            $course_page = route('courses.show', ['slug' => $lesson->course->slug]);
            foreach ($chapters as $key => $chapter) {
                $chapters[$key]->lessons = $chapters[$key]->lessons()->with(['mediaVideo', 'notes', 'mediaAudio', 'mediaPDF', 'downloadableMedia'])->get();
            }
            $results = [
                'lesson' => $lesson,
                'previous_lesson' => $previous_lesson,
                'next_lesson' => $next_lesson,
                'is_certified' => $is_certified,
                'course_progress' => $course_progress,
                'course' => $course,
                'chapters' => $chapters,
                'course_page' => $course_page,
                'course_timeline' => $courseSequence,
            ];
            return response()->json(['status' => 'success', 'result' => $results]);
        }
        return response()->json(['status' => 'failure']);
    }

    /**
     * Save Notes
     * @return Response JSON
     */
    public function saveNote(Request $request)
    {
        $note = Note::findorfail($request->id);
        $note->contentText = $request->contentText;
        $note->save();
        return response()->json(['status' => 'success', 'note' => $note]);
    }

    /**
     * Add New Note
     */
    public function AddNewNote(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $note = Note::create($data);
        return response()->json(['status' => 'success', 'note' => $note]);
    }

    /**
     * Remove Note
     */
    public function removeNote(Request $request)
    {
        $note = Note::findorFail($request->id);
        if ($note) {
            $note->delete();
            return response()->json(['status' => 'success', 'note' => $note]);
        }
        return response()->json(['status' => 'failure']);
    }

    /**
     * Get Test
     *
     * @return [json] Success message
     */

    public function getTest(Request $request)
    {
        $test = Test::where('published', '=', 1)
            ->where('slug', '=', $request->test)->with('questions')
            ->firstOrfail();
        if (!$test) {
            return response()->json(['status' => 'failure']);
        }
        $courseTimeLine = $test->course->courseTimeline()->with(['model'])->orderBy('sequence', 'asc')->get();
        $courseSequence = [];
        foreach ($courseTimeLine as $key => $item) {
            if ($item->model_type == Chapter::class) {
                $courseChapter[$item->id]['data'] = $item->model;
                $courseChapter[$item->id]['lessons'] = $test->course->courseTimeline()
                    ->with(['model', 'model.downloadableMedia', 'model.notes', 'model.mediaPDF', 'model.mediaAudio', 'model.mediaVideo'])
                    ->where('chapter_id', $item->model_id)
                    ->where('model_type', Lesson::class)
                    ->orderBy('sequence', 'asc')->get();
                $courseChapter[$item->id]['test'] = $test->course->courseTimeline()
                    ->with(['model', 'model.testResult', 'model.questions'])
                    ->where('chapter_id', $item->model_id)
                    ->where('model_type', Test::class)
                    ->orderBy('sequence', 'asc')->first();
                $prev_Chapter = $test->course->courseTimeline()->where('sequence', '<', $item->sequence)
                    ->where('model_type', Chapter::class)
                    ->where('course_id', $item->course_id)
                    ->orderBy('sequence', 'desc')->first();
                $prevChapter = null;
                if ($prev_Chapter) {
                    $prevChapter = Chapter::where('id', $prev_Chapter->model_id)->with(['test', 'test.testResult'])->first();
                }
                foreach ($courseChapter[$item->id]['lessons'] as $index => $chapterLesson) {
                    $chapterLesson['canView'] = true;
                    if ($index != 0) {
                        $prevLesson = $courseChapter[$item->id]['lessons'][$index - 1];
                        $LessonCompleted = auth()->user()->chapters()->where('model_id', $prevLesson->model_id)->first();
                        if (!$LessonCompleted) {
                            $chapterLesson['key'] = $index;
                            $chapterLesson['canView'] = false;
                        }
                    }
                    if ($prevChapter) {
                        $chapterLesson['key'] = $prev_Chapter->model_id;
                        $chapterTest = $prevChapter->test;
                        if ($chapterTest && count($chapterTest->testResult) > 0) {
                            if ($chapterTest->testResult[count($chapterTest->testResult) - 1]->test_result < $chapterTest->min_grade) {
                                $chapterLesson['canView'] = false;
                                $chapterLesson['key'] = $index . '- Failed';
                            }
                        } elseif ($chapterTest && count($chapterTest->testResult) == 0) {
                            $chapterLesson['canView'] = false;
                            $chapterLesson['key'] = $index . '- No result';
                        }
                    }
                }
                array_push($courseSequence, $courseChapter[$item->id]);
            }
        }
        $questions = [];
        $is_test_given = false;
        //If Retest is being taken
        if (isset($request->result_id)) {
            $testResult = TestsResult::where('id', '=', $request->result_id)
                ->where('user_id', '=', auth()->user()->id)
                ->delete();
            $is_test_given = false;
        }

        if ($test->questions && (count($test->questions) > 0)) {
            foreach ($test->questions as $question) {
                $options = [];
                if ($question->options) {
                    $options = $question->options->toArray();
                }

                $question_data['question'] = $question->toArray();
                $question_data['options'] = $options;

                $questions[] = $question_data;
            }
        }

        $test_result = TestsResult::where('test_id', $test->id)
            ->where('user_id', '=', auth()->user()->id)->orderBy('created_at', 'desc')
            ->first();
        $result_data = NULL;
        $questionsToAnswer = $test->questions()->with('options')->inRandomOrder()->take($test->no_questions);
        if ($test_result) {
            if ($test_result->attempts == 1) {
                $prevTestQuestions = $test_result->answers()->pluck('question_id');
                // $questionsToAnswer = $test->questions()->with('options')->whereNotIn('id', $prevTestQuestions)->inRandomOrder()->limit($test->no_questions);
                $questionsToAnswer = $test->questions()->with('options')->inRandomOrder()->take($test->no_questions);
            } elseif ($test_result->attempts == 2) {
                $questionsToAnswer = $test->questions()->with('options')->inRandomOrder();
            }

            $test_result = $test_result->toArray();
            $test->questions = $questionsToAnswer;
            $result = TestsResultsAnswer::where('tests_result_id', '=', $test_result['id'])->get()->toArray();
            $is_test_given = true;
            $result_data = ['result_id' => $test_result['id'], 'score' => $test_result, 'answers' => $result, 'attempts' => 0];
        }
        $test['totalScore'] = $questionsToAnswer->sum('score');
        $questionsToAnswer = $questionsToAnswer->get();
        $chapters = $test->course()->with('chapters')->first()->chapters;
        foreach ($chapters as $key => $chapter) {
            $chapters[$key]->lessons = $chapters[$key]->lessons()->with(['mediaVideo', 'notes', 'mediaAudio', 'mediaPDF', 'downloadableMedia'])->get();
        }

        $dt = time() + intval($test->timer * 60);

        $test['timer'] = Carbon::createFromTimestamp($dt, 'Africa/Cairo');
        $data['course_progress'] = $test->course->progress();
        $data['course_page'] = route('courses.show', ['slug' => $test->course->slug]);
        $test = $test->toArray();
        $test['questions'] = $questionsToAnswer;
        $data['test'] = $test;
        $data['course_timeline'] = $courseSequence;
        $data['is_test_given'] = $is_test_given;
        $data['test_result'] = $result_data;
        return response()->json(['status' => 'success', 'response' => $data]);
    }


    /**
     * Save Test
     *
     * @return [json] Success message
     */
    public function saveTest(Request $request)
    {
        $test = Test::where('id', $request->test_id)->firstOrFail();
        if (!$test) {
            return response()->json(['status' => 'failure']);
        }
        $attempts = 0;
        $testResult = TestsResult::where('test_id', '=', $request->test_id)
            ->where('user_id', '=', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($testResult) {
            $attempts = $testResult->attempts;
        }
        $answers = [];
        $test_score = 0;
        foreach ($request->question_data as $item) {
            $question_id = $item['question_id'];
            $answer_id = $item['ans_id'];
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
            'attempts' => $attempts + 1
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
        $courseTimeLine = $test->course->courseTimeline()->with(['model'])->orderBy('sequence', 'asc')->get();
        $courseSequence = [];
        foreach ($courseTimeLine as $key => $item) {
            if ($item->model_type == Chapter::class) {
                $courseChapter[$item->id]['data'] = $item->model;
                $courseChapter[$item->id]['lessons'] = $test->course->courseTimeline()
                    ->with(['model', 'model.downloadableMedia', 'model.notes', 'model.mediaPDF', 'model.mediaAudio', 'model.mediaVideo'])
                    ->where('chapter_id', $item->model_id)
                    ->where('model_type', Lesson::class)
                    ->orderBy('sequence', 'asc')->get();
                $courseChapter[$item->id]['test'] = $test->course->courseTimeline()
                    ->with(['model', 'model.testResult', 'model.questions'])
                    ->where('chapter_id', $item->model_id)
                    ->where('model_type', Test::class)
                    ->orderBy('sequence', 'asc')->first();
                $prev_Chapter = $test->course->courseTimeline()->where('sequence', '<', $item->sequence)
                    ->where('model_type', Chapter::class)
                    ->where('course_id', $item->course_id)
                    ->orderBy('sequence', 'desc')->first();
                $prevChapter = null;
                if ($prev_Chapter) {
                    $prevChapter = Chapter::where('id', $prev_Chapter->model_id)->with(['test', 'test.testResult'])->first();
                }
                foreach ($courseChapter[$item->id]['lessons'] as $index => $chapterLesson) {
                    $chapterLesson['canView'] = true;
                    if ($index != 0) {
                        $prevLesson = $courseChapter[$item->id]['lessons'][$index - 1];
                        $LessonCompleted = auth()->user()->chapters()->where('model_id', $prevLesson->model_id)->first();
                        if (!$LessonCompleted) {
                            $chapterLesson['key'] = $index;
                            $chapterLesson['canView'] = false;
                        }
                    }
                    if ($prevChapter) {
                        $chapterLesson['key'] = $prev_Chapter->model_id;
                        $chapterTest = $prevChapter->test;
                        if ($chapterTest && count($chapterTest->testResult) > 0) {
                            if ($chapterTest->testResult[count($chapterTest->testResult) - 1]->test_result < $chapterTest->min_grade) {
                                $chapterLesson['canView'] = false;
                                $chapterLesson['key'] = $index . '- Failed';
                            }
                        } elseif ($chapterTest && count($chapterTest->testResult) == 0) {
                            $chapterLesson['canView'] = false;
                            $chapterLesson['key'] = $index . '- No result';
                        }
                    }
                }
                array_push($courseSequence, $courseChapter[$item->id]);
            }
        }
        $result = TestsResultsAnswer::where('tests_result_id', '=', $test_result->id)->get()->toArray();

        return response()->json(['status' => 'success', 'resultData' => $test_result, 'score' => $test_score, 'result' => $result, 'course_timeline' => $courseSequence]);
    }


    /**
     * Complete Lesson
     *
     * @return [json] Success message
     */
    public function courseProgress(Request $request)
    {
        if ($request->model_type == 'test') {
            $model_type = Test::class;
            $chapter = Test::findorFail((int)$request->model_id);
        } else {
            $model_type = Lesson::class;
            $chapter = Lesson::findorFail((int)$request->model_id);
        }
        if ($chapter) {
            $modelExsits = auth()->user()->chapters()->where('model_id', $request->model_id)->first();
            if (!$modelExsits) {
                $chapter->chapterStudents()->create([
                    'model_type' => $model_type,
                    'model_id' => $request->model_id,
                    'user_id' => auth()->user()->id,
                    'course_id' => $chapter->course->id
                ]);
                return response()->json(['status' => 'success']);
            }
            return response()->json(['status' => 'error while adding the model' . $modelExsits]);
        }
        return response()->json(['status' => 'failure']);
    }

    /**
     * Save video progress for Lesson
     *
     * @return [json] Success message
     */
    public function videoProgress(Request $request)
    {
        $user = auth()->user();
        $video = Media::find($request->media_id);
        if ($video == null) {
            return response()->json(['status' => 'failure']);
        }
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
        return response()->json(['status' => 'success']);
    }


    /**
     * Generate course certificate
     *
     * @return [json] Success message
     */

    public function generateCertificate(Request $request)
    {
        $course = Course::whereHas('students', function ($query) {
            $query->where('id', \Auth::id());
        })->where('id', '=', $request->course_id)->first();
        if (($course != null) && ($course->progress() >= 100)) {
            $certificate = Certificate::firstOrCreate([
                'user_id' => auth()->user()->id,
                'course_id' => $request->course_id
            ]);

            $data = [
                'name' => auth()->user()->name,
                'course_name' => $course->title,
                'date' => Carbon::now()->format('d M, Y'),
            ];
            $certificate_name = 'Certificate-' . $course->id . '-' . auth()->user()->id . '.pdf';
            $certificate->name = auth()->user()->id;
            $certificate->url = $certificate_name;
            $certificate->save();

            $pdf = \PDF::loadView('certificate.index', compact('data'))->setPaper('', 'landscape');

            $pdf->save(public_path('storage/certificates/' . $certificate_name));

            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failure']);
    }


    /**
     * Get Bundles
     *
     * @return [json] Bundle Object
     */
    public function getBundles(Request $request)
    {
        $types = ['popular', 'trending', 'featured'];
        $type = ($request->type) ? $request->type : null;
        if ($type != null) {
            if (in_array($type, $types)) {
                $bundles = Bundle::where('published', '=', 1)
                    ->where($type, '=', 1)
                    ->paginate(10);
            } else {
                return response()->json(['status' => 'failure', 'message' => 'Invalid Request']);
            }
        } else {
            $bundles = Bundle::where('published', '=', 1)
                ->paginate(10);
        }

        return response()->json(['status' => 'success', 'type' => $type, 'result' => $bundles]);
    }

    /**
     * Get Bundles
     *
     * @return [json] Bundle Object
     */
    public function getSingleBundle(Request $request)
    {
        $result['bundle'] = Bundle::where('published', '=', 1)
            ->where('id', '=', $request->bundle_id)
            ->first();

        $purchased_bundle = \Auth::check() && $result['bundle']->students()->where('user_id', \Auth::id())->count() > 0;


        if ($result['bundle'] == null) {
            return response()->json(['status' => 'failure', 'message' => 'Invalid Request']);
        }
        $result['courses'] = $result['bundle']->courses;
        return response()->json(['status' => 'success', 'purchased_bundle' => $purchased_bundle, 'result' => $result]);
    }


    /**
     * Add to cart
     *
     * @return [json] Return cart value
     */




    /**
     * Get Free Course / Bundle
     *
     * @return [json] Success Message
     */
    public function getNow(Request $request)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = 0;
        $order->status = 1;
        $order->payment_type = 0;
        $order->save();
        //Getting and Adding items
        if ($request->course_id) {
            $type = Course::class;
            $id = $request->course_id;
        } else {
            $type = Bundle::class;
            $id = $request->bundle_id;
        }
        $order->items()->create([
            'item_id' => $id,
            'item_type' => $type,
            'price' => 0
        ]);

        foreach ($order->items as $orderItem) {
            //Bundle Entries
            if ($orderItem->item_type == Bundle::class) {
                foreach ($orderItem->item->courses as $course) {
                    $course->students()->attach($order->user_id);
                }
            }
            $orderItem->item->students()->attach($order->user_id);
        }

        return response()->json(['status' => 'success']);
    }


    /**
     * Remove from cart
     *
     * @return [json] Remove from cart
     */


    /**
     * Show Cart
     *
     * @return [json] Get Cart data
     */
    public function getCartData(Request $request)
    {
        $cartData = StudentCart::where('user_id', $request->user_id)->get();

        $courses = [];
        $bundles = [];
        foreach ($cartData as $i => $cart) {
            if ($cartData[$i]->item_type == 'course') {

                $courses[] = Course::where('id', $cartData[$i]->item_id)->with('teachers')->first();
            } elseif ($cartData[$i]->item_type == 'bundle') {
                $bundles = Bundles::where('id', $cartData[$i]->item_id)->first();
            }
        }

        return response()->json(['courses' => $courses, 'bundles' => $bundles]);
    }
    public function addToCart(Request $request)
    {

        $product = "";
        $teachers = "";
        $type = "";
        if ($request->type == 'course') {
            $product = Course::withoutGlobalScope('filter')->findOrFail($request->get('item_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
        } elseif ($request->type == 'bundle') {
            $product = Bundle::findOrFail($request->get('item_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        $cart = StudentCart::where('item_id', $request->item_id)->where('item_type', $request->type)->where('user_id', $request->user_id)->first();
        if (!$cart) {

            $cart = new StudentCart();
            $cart->user_id = $request->user_id;
            $cart->item_id = $request->item_id;
            $cart->item_type = $request->type;

            $cart->save();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'Already Added To Your Cart !']);
        }
    }
    public function removeFromCart(Request $request)
    {
        $cart = StudentCart::where('item_id', $request->item_id)->where('item_type', $request->item_type)->where('user_id', $request->user_id);
        //    return $cart;
        $cart->delete();

        // foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
        //     if (($cartItem->attributes->type == $request->type) && ($cartItem->attributes->product_id == $request->item_id)) {
        //         Cart::session(auth()->user()->id)->remove($request->item_id);
        //     }
        // }
        return response()->json(['status' => 'success']);
    }

    // return session('cart');

    // return redirect()->back()->with(['success' => trans('labels.frontend.cart.product_added')]);

    /**
     * Clear Cart
     *
     * @return [json] Success Message
     */
    public function clearCart()
    {
        Cart::session(auth()->user()->id)->clear();
        return response()->json(['status' => 'success']);
    }



    /**
     * Payment Status
     *
     * @return [json] Success Message
     */
    public function checkout(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        $bundle_ids = [];
        $course_ids = [];
        if ($request->type == 'course') {
            $product = Course::withoutGlobalScope('filter')->findOrFail($request->get('item_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
        } elseif ($request->type == 'bundle') {
            $product = Bundle::findOrFail($request->get('item_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }
        // if ($request->has('course_id')) {
        //     $product = Course::withoutGlobalScope('filter')->findOrFail($request->get('course_id'));
        //     $teachers = $product->teachers->pluck('id', 'name');
        //     $type = 'course';

        // } elseif ($request->has('bundle_id')) {
        //     $product = Bundle::findOrFail($request->get('bundle_id'));
        //     $teachers = $product->user->name;
        //     $type = 'bundle';
        // }
        $cart_items = StudentCart::where('user_id', $request->user_id)->get();

        // $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        // if (!in_array($product->id, $cart_items)) {

        //     Cart::session(auth()->user()->id)
        //         ->add($product->id, $product->title, $product->price, 1,
        //             [
        //                 'user_id' => auth()->user()->id,
        //                 'description' => $product->description,
        //                 'image' => $product->course_image,
        //                 'type' => $type,
        //                 'teachers' => $teachers
        //             ]);
        // }
        foreach ($cart_items as $item) {
            if ($item->item_type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::withoutGlobalScope('filter')->find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = Cart::session(auth()->user()->id)->getContent()->sum('price');


        //Apply Tax
        $taxData = $this->applyTax('total');


        return view('frontend.cart.checkout', compact('courses', 'total', 'taxData'));
    }
    public function paymentStatus(Request $request)
    {
        $counter = 0;
        $items = [];
        $order = Order::where('id', '=', (int)$request->order_id)->where('status', '=', 0)->first();
        if ($order) {
            $order->payment_type = $request->payment_type;
            $order->status = ($request->status == 'success') ? 1 : 0;
            $order->remarks = $request->remarks;
            $order->transaction_id = $request->transaction_id;
            $order->save();
            if ($order->status == 1) {
                (new EarningHelper())->insert($order);
            }
            if ((int)$request->payment_type == 3) {
                foreach ($order->items as $key => $cartItem) {
                    $counter++;
                    array_push($items, ['number' => $counter, 'name' => $cartItem->item->name, 'price' => $cartItem->item->price]);
                }

                $content['items'] = $items;
                $content['total'] = $order->amount;
                $content['reference_no'] = $order->reference_no;

                try {
                    \Mail::to(auth()->user()->email)->send(new OfflineOrderMail($content));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order ' . $order->id);
                }
            } else {
                foreach ($order->items as $orderItem) {
                    //Bundle Entries
                    if ($orderItem->item_type == Bundle::class) {
                        foreach ($orderItem->item->courses as $course) {
                            $course->students()->attach($order->user_id);
                        }
                    }
                    $orderItem->item->students()->attach($order->user_id);
                }

                //Generating Invoice
                generateInvoice($order);
            }

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'failure', 'message' => 'No order found']);
        }
    }


    public function offlinePayment(Request $request)
    {

        //Making Order
        $order = $this->makeOrder($request->data);
        $order->payment_type = 3;
        $order->status = 0;
        $order->save();
        $content = [];
        $items = [];
        $courses = [];
        $bundles = [];
        $counter = 0;
        $price = 0;
        // foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
        //     $counter++;
        //     array_push($items, ['number' => $counter, 'name' => $cartItem->name, 'price' => $cartItem->price]);
        // }

        $cartItems = StudentCart::where('user_id', auth()->user()->id)->get();
        foreach ($cartItems as $key => $item) {
            if ($cartItems[$key]->item_type == "course") {
                $courses = Course::where('id', $cartItems[$key]->item_id)->get();
            } else {
                $bundles = Bundle::where('id', $cartItems[$key]->item_id)->get();
            }

            foreach ($courses as $i => $course) {
                $counter++;
                array_push($items, ['number' => $counter, 'name' => $course->title, 'price' => $course->price]);
            }


            // foreach($bundles as $i=>$course){
            //     $counter++;
            //     array_push($items, ['number' => $counter, 'name' => $course->title, 'price' => $course->price]);

            // }


            $content['items'] = $items;
            // $content['total'] = Cart::session(auth()->user()->id)->getTotal();
            $content[$total] = $courses->sum('price');
            $content['reference_no'] = $order->reference_no;

            try {
                \Mail::to(auth()->user()->email)->send(new OfflineOrderMail($content));
            } catch (\Exception $e) {
                \Log::info($e->getMessage() . ' for order ' . $order->id);
            }

            if ($request->coupon) {
                $coupon_code = $request->coupon;
                $coupon = Coupon::where('code', '=', $coupon_code)
                    ->where('status', '=', 1)
                    ->first();
                $coupon->status = 2;
                $coupon->save();
                $order->status = 1;
                $order->save();
                foreach ($order->items as $orderItem) {
                    //Bundle Entries
                    if ($orderItem->item_type == Bundle::class) {
                        foreach ($orderItem->item->courses as $course) {
                            $course->students()->attach($order->user_id);
                        }
                    }
                    $orderItem->item->students()->attach($order->user_id);
                }
            }

            //Generating Invoice
            generateInvoice($order);


            $cartItems = StudentCart::where('user_id', auth()->user()->id)->delete();

            return response()->json(['status' => 'success']);
        }
    }


    /**
     * Create Order
     *
     * @return [json] Order
     */
    private function makeOrderOld()
    {
        $coupon = Cart::session(auth()->user()->id)->getConditionsByType('coupon')->first();
        if ($coupon != null) {
            $coupon = Coupon::where('code', '=', $coupon->getName())->first();
        }
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = Cart::session(auth()->user()->id)->getTotal();
        $order->status = 1;
        $order->coupon_id = ($coupon == null) ? 0 : $coupon->id;
        $order->payment_type = 3;
        $order->save();
        //Getting and Adding items
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'item_type' => $type,
                'price' => $cartItem->price
            ]);
        }
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');

        return $order;
    }

    private function makeOrder($data)
    {
        $coupon = $data['coupon_data'];
        if ($coupon != false) {
            $coupon_id = $coupon['id'];
        } else {
            $coupon_id = 0;
        }
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = $data['final_total'];
        $order->status = 0;
        $order->coupon_id = $coupon_id;
        $order->payment_type = 0;
        $order->save();
        //Getting and Adding items
        foreach ($data['data'] as $item) {
            if ($item['type'] == 'bundle') {
                $type = Bundle::class;
            } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $item['id'],
                'item_type' => $type,
                'price' => $item['price']
            ]);
        }

        return $order;
    }


    /**
     * Create Order
     *
     * @return [json] Order
     */
    public function getBlog(Request $request)
    {

        if ($request->blog_id != null) {
            $blog_id = $request->blog_id;
            $blog = Blog::with('comments', 'category', 'author')->find($blog_id);
            // get previous user id
            $previous_id = Blog::where('id', '<', $blog_id)->max('id');
            $previous = Blog::find($previous_id);

            // get next user id
            $next_id = Blog::where('id', '>', $blog_id)->min('id');
            $next = Blog::find($next_id);

            return response()->json(['status' => 'success', 'blog' => $blog, 'next' => $next_id, 'previous' => $previous_id]);
        }


        $blog = Blog::has('category')->with('comments')->OrderBy('created_at', 'desc')->paginate(10);
        return response()->json(['status' => 'success', 'blog' => $blog]);
    }


    /**
     * Blog By Category
     *
     * @return [json] Blog List
     */
    public function getBlogByCategory(Request $request)
    {
        $category = Category::find((int)$request->category_id);
        if ($category != null) {
            $blog = $category->blogs()->paginate(10);
            return response()->json(['status' => 'success', 'result' => $blog]);
        }
        return response()->json(['status' => 'failure']);
    }


    /**
     * Blog By Tag
     *
     * @return [json] Blog List
     */
    public function getBlogByTag(Request $request)
    {
        $tag = Tag::find((int)$request->tag_id);
        if ($tag != "") {
            $blog = $tag->blogs()->paginate(10);
            return response()->json(['status' => 'success', 'result' => $blog]);
        }
        return response()->json(['status' => 'failure']);
    }


    /**
     * Blog Store Comment
     *
     * @return [json] Success Message
     */
    public function addBlogComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required|min:3',
        ]);
        $blog = Blog::find($request->blog_id);
        if ($blog != null) {
            $blogcooment = new BlogComment($request->all());
            $blogcooment->name = auth()->user()->full_name;
            $blogcooment->email = auth()->user()->email;
            $blogcooment->comment = $request->comment;
            $blogcooment->blog_id = $blog->id;
            $blogcooment->user_id = auth()->user()->id;
            $blogcooment->save();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failure']);
    }


    /**
     * Blog Delete Comment
     *
     * @return [json] Success Message
     */
    public function deleteBlogComment(Request $request)
    {
        $comment = BlogComment::find((int)$request->comment_id);
        if (auth()->user()->id == $comment->user_id) {
            $comment->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failure']);
    }


    /**
     * Forums home
     *
     * @return [json] forum object
     */

    public function getForum(Request $request)
    {

        $pagination_results = config('chatter.paginate.num_of_results');

        $discussions = Models::discussion()->with('user')->with(['post', 'post.user'])->with('postsCount')->with('category')->orderBy(config('chatter.order_by.discussions.order'), config('chatter.order_by.discussions.by'));
        if (isset($slug)) {
            $category = Models::category()->where('slug', '=', $slug)->first();

            if (isset($category->id)) {
                $current_category_id = $category->id;
                $discussions = $discussions->where('chatter_category_id', '=', $category->id);
            } else {
                $current_category_id = null;
            }
        }

        $discussions = $discussions->paginate($pagination_results);

        $categories = Models::category()->get();

        $result = [
            'discussions' => $discussions,
            'categories' => $categories,
        ];

        return response()->json(['status' => 'success', 'result' => $result]);
    }

    /**
     * Create Discussion
     *
     * @return [json] success message
     */

    public function createDiscussion(Request $request)
    {
        $request->request->add(['body_content' => strip_tags($request->body)]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:255',
            'body_content' => 'required|min:10',
            'chatter_category_id' => 'required',
        ], [
            'title.required' => trans('chatter::alert.danger.reason.title_required'),
            'title.min' => [
                'string' => trans('chatter::alert.danger.reason.title_min'),
            ],
            'title.max' => [
                'string' => trans('chatter::alert.danger.reason.title_max'),
            ],
            'body_content.required' => trans('chatter::alert.danger.reason.content_required'),
            'body_content.min' => trans('chatter::alert.danger.reason.content_min'),
            'chatter_category_id.required' => trans('chatter::alert.danger.reason.category_required'),
        ]);


        Event::fire(new ChatterBeforeNewDiscussion($request, $validator));
        if (function_exists('chatter_before_new_discussion')) {
            chatter_before_new_discussion($request, $validator);
        }

        $user_id = Auth::user()->id;

        if (config('chatter.security.limit_time_between_posts')) {
            if ($this->notEnoughTimeBetweenDiscussion()) {
                $minutes = trans_choice('chatter::messages.words.minutes', config('chatter.security.time_between_posts'));

                return response()->json(['status' => 'failure', 'result' => trans('chatter::alert.danger.reason.prevent_spam', [
                    'minutes' => $minutes,
                ])]);
            }
        }

        // *** Let's gaurantee that we always have a generic slug *** //
        $slug = str_slug($request->title, '-');

        $discussion_exists = Models::discussion()->where('slug', '=', $slug)->withTrashed()->first();
        $incrementer = 1;
        $new_slug = $slug;
        while (isset($discussion_exists->id)) {
            $new_slug = $slug . '-' . $incrementer;
            $discussion_exists = Models::discussion()->where('slug', '=', $new_slug)->withTrashed()->first();
            $incrementer += 1;
        }

        if ($slug != $new_slug) {
            $slug = $new_slug;
        }

        $new_discussion = [
            'title' => $request->title,
            'chatter_category_id' => $request->chatter_category_id,
            'user_id' => $user_id,
            'slug' => $slug,
            'color' => '#0c0919',
        ];

        $category = Models::category()->find($request->chatter_category_id);
        if (!isset($category->slug)) {
            $category = Models::category()->first();
        }

        $discussion = Models::discussion()->create($new_discussion);

        $new_post = [
            'chatter_discussion_id' => $discussion->id,
            'user_id' => $user_id,
            'body' => $request->body,
        ];

        if (config('chatter.editor') == 'simplemde') :
            $new_post['markdown'] = 1;
        endif;

        // add the user to automatically be notified when new posts are submitted
        $discussion->users()->attach($user_id);

        $post = Models::post()->create($new_post);

        if ($post->id) {
            Event::fire(new ChatterAfterNewDiscussion($request, $discussion, $post));
            if (function_exists('chatter_after_new_discussion')) {
                chatter_after_new_discussion($request);
            }

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'failure', 'result' => 'Not found']);
        }
    }


    /**
     * Create Response for Discussion
     *
     * @return [json] success message
     */
    public function storeResponse(Request $request)
    {
        $stripped_tags_body = ['body' => strip_tags($request->body)];
        $validator = Validator::make($stripped_tags_body, [
            'body' => 'required|min:10',
        ], [
            'body.required' => trans('chatter::alert.danger.reason.content_required'),
            'body.min' => trans('chatter::alert.danger.reason.content_min'),
        ]);

        Event::fire(new ChatterBeforeNewResponse($request, $validator));
        if (function_exists('chatter_before_new_response')) {
            chatter_before_new_response($request, $validator);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $request->request->add(['user_id' => Auth::user()->id]);

        if (config('chatter.editor') == 'simplemde') :
            $request->request->add(['markdown' => 1]);
        endif;

        $new_post = Models::post()->create($request->all());

        $discussion = Models::discussion()->find($request->chatter_discussion_id);

        $category = Models::category()->find($discussion->chatter_category_id);
        if (!isset($category->slug)) {
            $category = Models::category()->first();
        }

        if ($new_post->id) {
            $discussion->last_reply_at = $discussion->freshTimestamp();
            $discussion->save();

            Event::fire(new ChatterAfterNewResponse($request, $new_post));
            if (function_exists('chatter_after_new_response')) {
                chatter_after_new_response($request);
            }

            // if email notifications are enabled
            if (config('chatter.email.enabled')) {
                // Send email notifications about new post
                $this->sendEmailNotifications($new_post->discussion);
            }


            return response()->json(['status' => 'success', 'message' => trans('chatter::alert.success.reason.submitted_to_post')]);
        } else {
            return response()->json(['status' => 'failure', 'message' => trans('chatter::alert.danger.reason.trouble')]);
        }
    }


    /**
     * Update the Response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return [json] success message
     */
    public function updateResponse(Request $request)
    {
        $id = $request->post_id;
        $stripped_tags_body = ['body' => strip_tags($request->body)];
        $validator = Validator::make($stripped_tags_body, [
            'body' => 'required|min:10',
        ], [
            'body.required' => trans('chatter::alert.danger.reason.content_required'),
            'body.min' => trans('chatter::alert.danger.reason.content_min'),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $post = Models::post()->find($id);
        if (!Auth::guest() && (Auth::user()->id == $post->user_id)) {
            if ($post->markdown) {
                $post->body = strip_tags($request->body);
            } else {
                $post->body = Purifier::clean($request->body);
            }
            $post->save();

            $discussion = Models::discussion()->find($post->chatter_discussion_id);

            $category = Models::category()->find($discussion->chatter_category_id);
            if (!isset($category->slug)) {
                $category = Models::category()->first();
            }

            return response()->json(['status' => 'success', 'message' => trans('chatter::alert.success.reason.updated_post')]);
        } else {

            return response()->json(['status' => 'failure', 'message' => trans('chatter::alert.danger.reason.update_post')]);
        }
    }

    /**
     * Delete Response.
     *
     * @param string $id
     * @param \Illuminate\Http\Request
     *
     * @return [json] success message
     */
    public function deleteResponse(Request $request)
    {
        $id = $request->post_id;

        $post = Models::post()->with('discussion')->findOrFail($id);

        if ($request->user()->id !== (int)$post->user_id) {

            return response()->json(['status' => 'failure', 'message' => trans('chatter::alert.danger.reason.destroy_post')]);
        }

        if ($post->discussion->posts()->oldest()->first()->id === $post->id) {
            if (config('chatter.soft_deletes')) {
                $post->discussion->posts()->delete();
                $post->discussion()->delete();
            } else {
                $post->discussion->posts()->forceDelete();
                $post->discussion()->forceDelete();
            }

            return response()->json(['status' => 'success', 'message' => trans('chatter::alert.success.reason.destroy_post')]);
        }

        $post->delete();

        return response()->json(['status' => 'success', 'message' => trans('chatter::alert.success.reason.destroy_from_discussion')]);
    }


    /**
     * Get Conversations.
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] messages
     */

    public function getMessages(Request $request)
    {
        $thread = "";

        $teachers = User::role('teacher')->select('id', 'first_name', 'last_name')->get();

        auth()->user()->load('threads.messages.sender');

        $unreadThreads = [];
        $threads = [];
        foreach (auth()->user()->threads as $item) {
            if ($item->unreadMessagesCount > 0) {
                $unreadThreads[] = $item;
            } else {
                $threads[] = $item;
            }
        }
        $threads = Collection::make(array_merge($unreadThreads, $threads));

        if (request()->has('thread') && ($request->thread != null)) {

            if (request('thread')) {
                $thread = auth()->user()->threads()
                    ->where('message_threads.id', '=', $request->thread)
                    ->first();
                if ($thread == "") {
                    return response()->json(['status' => 'failure', 'Not found']);
                }
                //Read Thread
                auth()->user()->markThreadAsRead($thread->id);
            }
        }


        return response()->json([
            'status' => 'success', 'threads' => $threads,
            'teachers' => $teachers,
            'thread' => $thread
        ]);
    }


    /**
     * Create Message
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Success Message
     */
    public function composeMessage(Request $request)
    {
        $recipients = $request->data['recipients'];
        $message = $request->data['message'];


        $message = Messenger::from(auth()->user())->to($recipients)->message($message)->send();
        return response()->json(['status' => 'success', 'thread' => $message->thread_id]);
    }


    /**
     * Reply Message
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Success Message
     */
    public function replyMessage(Request $request)
    {

        $thread = auth()->user()->threads()
            ->where('message_threads.id', '=', $request->thread_id)
            ->first();
        $message = Messenger::from(auth()->user())->to($thread)->message($request->message)->send();
        return response()->json(['status' => 'success', 'thread' => $message->thread_id]);
    }

    /**
     * Get Unread Messages
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Success Message
     */
    public function getUnreadMessages(Request $request)
    {
        $unreadMessageCount = auth()->user()->unreadMessagesCount;
        $unreadThreads = [];
        foreach (auth()->user()->threads as $item) {
            if ($item->unreadMessagesCount > 0) {
                $data = [
                    'thread_id' => $item->id,
                    'message' => str_limit($item->lastMessage->body, 35),
                    'unreadMessagesCount' => $item->unreadMessagesCount,
                    'title' => $item->title
                ];
                $unreadThreads[] = $data;
            }
        }
        return response()->json(['status' => 'success', 'unreadMessageCount' => $unreadMessageCount, 'threads' => $unreadThreads]);
    }


    /**
     * Get My Certificates
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] certificates object
     */
    public function getMyCertificates()
    {
        $certificates = auth()->user()->certificates;

        return response()->json(['status' => 'success', 'result' => $certificates]);
    }


    /**
     * Get My Courses / Bundles / Purchases
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] certificates object
     */
    public function getMyPurchases()
    {

        $purchased_courses = auth()->user()->purchasedCourses();
        $courses = Course::wherein('id', $purchased_courses)->get();
        $unCompletedCourses = [];
        foreach ($courses as $i => $course) {
            if ($courses[$i]->progress() < 100) {
                $unCompletedCourses[]  = $courses[$i];
            }
        }

        $purchased_bundles = auth()->user()->purchasedBundles();


        return response()->json(['status' => 'success', 'result' => ['courses' => $purchased_courses, 'bundles' => $purchased_bundles, 'UnCompletedCourses' => $unCompletedCourses]]);
    }


    /**
     * Get My Account
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Loggedin user object
     */
    public function getMyAccount()
    {
        $id = auth()->user()->id;
        $user = User::with('roles', 'permissions', 'providers', 'studentData')
            ->where('id', $id)->first();
        return response()->json(['status' => 'success', 'result' => $user]);
    }


    /**
     * Update My Account
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Update account
     */
    public function updateMyAccount(Request $request)
    {
        $fieldsList = [];
        if (config('registration_fields') != NULL) {
            $fields = json_decode(config('registration_fields'));

            foreach ($fields as $field) {
                $fieldsList[] = '' . $field->name;
            }
        }
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('ar_first_name', 'ar_last_name', 'phone', 'avatar_location', 'avatar_type'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return response()->json(['status' => 'success', 'message' => __('strings.frontend.user.email_changed_notice')]);
        }

        return response()->json(['status' => 'success', 'message' => __('strings.frontend.user.profile_updated')]);
    }

    /**
     * Update Password
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Update password
     */
    public function updatePassword(Request $request)
    {


        $user = auth()->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => $request->password]);
            return response()->json(['status' => 'success', 'message' => __('strings.frontend.user.password_updated')]);
        } else {
            return response()->json(['status' => 'failed', 'message' => __('Incorrect Password')]);
        }
    }




    /**
     * Update Pages (About-us)
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] Update password
     */
    public function getPage()
    {
        $page = Page::where('slug', '=', request('page'))
            ->where('published', '=', 1)->first();
        if ($page != "") {
            return response()->json(['status' => 'success', 'result' => $page]);
        }
        return response()->json(['status' => 'failure', 'result' => NULL]);
    }

    public function getInvoices()
    {

        $invoices = auth()->user()->invoices()->whereHas('order')->get();
        return ['status' => 'success', 'invoices' => $invoices];
    }

    public function showInvoice(Request $request)
    {


        if (auth()->check()) {
            $hashid = new Hashids('', 5);
            $order_id = $hashid->decode($request->code);
            $order_id = array_first($order_id);

            $order = Order::findOrFail($order_id);
            if (auth()->user()->isAdmin() || ($order->user_id == auth()->user()->id)) {
                if (Storage::exists('invoices/' . $order->invoice->url)) {
                    return response()->file(Storage::path('invoices/' . $order->invoice->url));
                }
                return abort(404);
            }
        }
        return abort(404);
    }


    /**
     * Subscribe newsletter
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] response
     */

    public function subscribeNewsletter(Request $request)
    {
        if (config('mail_provider') != NULL && config('mail_provider') == "mailchimp") {
            try {
                if (!Newsletter::isSubscribed($request->email)) {
                    if (config('mailchimp_double_opt_in')) {
                        Newsletter::subscribePending($request->email);
                        $message = "We've sent you an email, Check your mailbox for further procedure.";
                    } else {
                        Newsletter::subscribe($request->email);
                        $message = "You've subscribed successfully";
                    }
                    return response()->json(['status' => 'success', 'message' => $message]);
                } else {
                    $message = "Email already exist in subscription list";
                    return response()->json(['status' => 'failure', 'message' => $message]);
                }
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
                $message = "Something went wrong, Please try again Later";
                return response()->json(['status' => 'failure', 'message' => $message]);
            }
        } elseif (config('mail_provider') != NULL && config('mail_provider') == "sendgrid") {
            try {
                $apiKey = config('sendgrid_api_key');
                $sg = new \SendGrid($apiKey);
                $query_params = json_decode('{"page": 1, "page_size": 1}');
                $response = $sg->client->contactdb()->recipients()->get(null, $query_params);
                if ($response->statusCode() == 200) {
                    $users = json_decode($response->body());
                    $emails = [];
                    foreach ($users->recipients as $user) {
                        array_push($emails, $user->email);
                    }
                    if (in_array($request->email, $emails)) {
                        $message = "Email already exist in subscription list";
                        return response()->json(['status' => 'failure', 'message' => $message]);
                    } else {
                        $request_body = json_decode(
                            '[{
                             "email": "' . $request->email . '",
                             "first_name": "",
                             "last_name": ""
                              }]'
                        );
                        $response = $sg->client->contactdb()->recipients()->post($request_body);
                        if ($response->statusCode() != 201 || (json_decode($response->body())->new_count == 0)) {

                            $message = "Email already exist in subscription list";
                            return response()->json(['status' => 'failure', 'message' => $message]);
                        } else {
                            $recipient_id = json_decode($response->body())->persisted_recipients[0];
                            $list_id = config('sendgrid_list');
                            $response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->_($recipient_id)->post();
                            if ($response->statusCode() == 201) {
                                session()->flash('alert', "You've subscribed successfully");
                            } else {
                                $message = "Check your email and try again";
                                return response()->json(['status' => 'failure', 'message' => $message]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
                $message = "Something went wrong, Please try again Later";
                return response()->json(['status' => 'failure', 'message' => $message]);
            }
        }
        return response()->json(['status' => 'failure', 'message' => 'Please setup mail provider in Admin dashboard on server']);
    }


    /**
     * Get Offers
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] response
     */
    public function getOffers()
    {
        $coupons = Coupon::where('status', '=', 1)->get();
        return ['status' => 'success', 'coupons' => $coupons];
    }


    /**
     * Apply Coupon
     *
     * @param \Illuminate\Http\Request
     *
     * @return [json] response
     */
    public function applyCouponOld(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');

        $coupon = $request->coupon;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();
        if ($coupon != null) {
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');

            $ids = Cart::session(auth()->user()->id)->getContent()->keys();
            $course_ids = [];
            $bundle_ids = [];
            foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
                if ($item->attributes->type == 'bundle') {
                    $bundle_ids[] = $item->id;
                } else {
                    $course_ids[] = $item->id;
                }
            }
            $courses = new Collection(Course::find($course_ids));
            $bundles = Bundle::find($bundle_ids);
            $courses = $bundles->merge($courses);

            $total = $courses->sum('price');
            $isCouponValid = false;

            if ($coupon->per_user_limit > $coupon->useByUser()) {
                $isCouponValid = true;
                if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
                    if ($total >= $coupon->min_price) {
                        $isCouponValid = true;
                    }
                } else {
                    $isCouponValid = true;
                }
            }

            if ($coupon->expires_at != null) {
                if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
                    $isCouponValid = true;
                } else {
                    $isCouponValid = false;
                }
            }

            if ($isCouponValid == true) {
                $type = null;
                if ($coupon->type == 1) {
                    $type = '-' . $coupon->amount . '%';
                } else {
                    $type = '-' . $coupon->amount;
                }

                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                    'value' => $type,
                    'order' => 1
                ));

                Cart::session(auth()->user()->id)->condition($condition);
                //Apply Tax
                $taxData = $this->applyTax('subtotal');


                return ['status' => 'success'];
            }
        }
        return ['status' => 'failure', 'message' => trans('labels.frontend.cart.invalid_coupon')];
    }

    public function applyCoupon(Request $request)
    {
        $data = [];
        $items = [];
        $total = 0;
        $coupon = $request->coupon;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();
        $isCouponValid = false;
        if ($coupon != null) {

            if (count($request->data) > 0) {
                foreach ($request->data as $item) {
                    $id = $item['id'];
                    $price = $item['price'];
                    if ($item['type'] == 'bundle') {
                        $status = false;
                        $bundle = Bundle::where('id', '=', $item['id'])
                            ->where('price', '=', $item['price'])
                            ->where('published', '=', 1)
                            ->first();
                        if ($bundle) {
                            $status = true;
                            $total = $total + $bundle->price;
                        }
                        $bundle = [
                            'id' => $id,
                            'type' => 'bundle',
                            'price' => $price,
                            'status' => $status
                        ];
                        array_push($items, $bundle);
                    } else {
                        $status = false;

                        $course = Course::where('id', '=', $id)
                            ->where('price', '=', $price)
                            ->where('published', '=', 1)
                            ->first();
                        if ($course) {
                            $status = true;
                            $total = $total + $course->price;
                        }
                        $course = [
                            'id' => $id,
                            'type' => 'course',
                            'price' => $price,
                            'status' => $status
                        ];
                        array_push($items, $course);
                    }
                }
                $data['data'] = $items;

                $total = (float)number_format($total, 2);

                if ((float)$request->total == $total) {

                    if ($coupon->per_user_limit > $coupon->useByUser()) {
                        $isCouponValid = true;
                        if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
                            if ($total >= $coupon->min_price) {
                                $isCouponValid = true;
                            }
                        } else {
                            $isCouponValid = true;
                        }
                    }

                    if ($coupon->expires_at != null) {
                        if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
                            $isCouponValid = true;
                        } else {
                            $isCouponValid = false;
                        }
                    }

                    if ($isCouponValid == true) {

                        $type = null;
                        if ($coupon->type == 1) {
                            $discount = $total * $coupon->amount / 100;
                        } else {
                            $discount = $coupon->amount;
                        }
                        $data['subtotal'] = (float)number_format($total, 2);

                        //$data['discounted_total'] = (float)number_format($total - $discount,2);
                        $data['coupon_data'] = $coupon->toArray();
                        $data['coupon_data']['total_coupon_discount'] = (float)number_format($discount, 2);


                        //Apply Tax
                        $data['tax_data'] = $this->applyTax($total);
                        $tax_amount = $data['tax_data']['total_tax'];

                        $discount = $data['coupon_data']['total_coupon_discount'];
                        $data['final_total'] = ($total - $discount) + $tax_amount;


                        return ['status' => 'success', 'result' => $data];
                    } else {
                        return ['status' => 'failure', 'message' => 'Coupon is Invalid'];
                    }
                } else {
                    return ['status' => 'failure', 'message' => 'Total Mismatch', 'result' => $data];
                }
            }
            return ['status' => 'failure', 'message' => 'Add Items to Cart before applying coupon'];
        }
        return ['status' => 'failure', 'message' => 'Please input valid coupon'];
    }


    public function orderConfirmation(Request $request)
    {
        $data = [];
        $items = [];
        $total = 0;
        if (count($request->data) > 0) {
            foreach ($request->data as $item) {
                $id = $item['id'];
                $price = $item['price'];
                if ($item['type'] == 'bundle') {
                    $status = false;
                    $bundle = Bundle::where('id', '=', $item['id'])
                        ->where('price', '=', $item['price'])
                        ->where('published', '=', 1)
                        ->first();
                    if ($bundle) {
                        $status = true;
                        $total = $total + $bundle->price;
                    }
                    $bundle = [
                        'id' => $id,
                        'type' => 'bundle',
                        'price' => $price,
                        'status' => $status
                    ];
                    array_push($items, $bundle);
                } else {
                    $status = false;

                    $course = Course::where('id', '=', $id)
                        ->where('price', '=', $price)
                        ->where('published', '=', 1)
                        ->first();
                    if ($course) {
                        $status = true;
                        $total = $total + $course->price;
                    }
                    $course = [
                        'id' => $id,
                        'type' => 'course',
                        'price' => $price,
                        'status' => $status
                    ];
                    array_push($items, $course);
                }
            }
            $data['data'] = $items;

            if ((float)$request->total == floatval($total)) {

                $coupon = $request->coupon;
                $discount = 0;
                $tax_amount = 0;
                $coupon = Coupon::where('code', '=', $coupon)
                    ->where('status', '=', 1)
                    ->first();

                $type = null;
                if ($coupon) {
                    if ($coupon->type == 1) {
                        $discount = $total * $coupon->amount / 100;
                    } else {
                        $discount = $coupon->amount;
                    }
                    //$data['discounted_total'] = (float)number_format($total - $discount,2);
                    $data['coupon_data'] = $coupon->toArray();
                    $data['coupon_data']['total_coupon_discount'] = (float)number_format($discount, 2);
                    $discount = $data['coupon_data']['total_coupon_discount'];
                } else {
                    $data['coupon_data'] = false;
                }


                $data['subtotal'] = (float)$total;
                $total = $total - $discount;

                //Apply Tax
                $data['tax_data'] = $this->applyTax($total);
                if ($data['tax_data'] != 0) {
                    $tax_amount = $data['tax_data']['total_tax'];
                }

                $data['final_total'] = $total + $tax_amount;

                $order = $this->makeOrder($data);
                $data['order'] = $order;

                return $data;
            } else {
                return ['status' => 'failure', 'message' => 'Total Mismatch', 'result' => $data];
            }
        }
        return ['status' => 'failure', 'message' => 'Add Items to Cart before applying coupon'];
    }

    public function removeCoupon(Request $request)
    { //Obsolete

        Cart::session(auth()->user()->id)->clearCartConditions();
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');

        $course_ids = [];
        $bundle_ids = [];
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        //Apply Tax
        $this->applyTax('subtotal');

        return ['status' => 'success'];
    }

    private function notEnoughTimeBetweenDiscussion()
    {
        $user = Auth::user();

        $past = Carbon::now()->subMinutes(config('chatter.security.time_between_posts'));

        $last_discussion = Models::discussion()->where('user_id', '=', $user->id)->where('created_at', '>=', $past)->first();

        if (isset($last_discussion)) {
            return true;
        }

        return false;
    }

    private function sendEmailNotifications($discussion)
    {
        $users = $discussion->users->except(Auth::user()->id);
        foreach ($users as $user) {
            \Mail::to($user)->queue(new ChatterDiscussionUpdated($discussion));
        }
    }

    public function getConfigs()
    {
        $currency = getCurrency(config('app.currency'));
        return response()->json(['status' => 'success', 'result' => $currency]);
    }

    private function applyTax($total)
    {
        //Apply Conditions on Cart
        $taxes = Tax::where('status', '=', 1)->get();
        if (count($taxes) > 0) {
            $taxData = [];
            $taxDetails = [];
            $amounts = [];
            foreach ($taxes as $tax) {
                $amount = $total * ((float)$tax->rate / 100);
                $amounts[] = $amount;
                $taxMeta = [
                    'name' => (float)$tax->rate . '% ' . $tax->name,
                    'amount' => (float)$amount
                ];
                array_push($taxDetails, $taxMeta);
            }
            $taxData['taxes'] = $taxDetails;
            $taxData['total_tax'] = array_sum($amounts);

            return $taxData;
        }
        return false;
    }

    public function getCountries()
    {
        $countries = Country::with('eduSystems')->get();
        return response()->json(['success' => true, 'data' => $countries]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveCountry(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'key' => 'required|unique:countries',
        ]);
        if ($validator->passes()) {
            $country = new Country();
            $country->ar_name = $request->ar_name;
            $country->en_name = $request->en_name;
            $country->key = $request->key;
            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path('storage/flags'), $name);
            $url =  env('APP_URL') . '/storage/flags/' . $name;
            $country->image = $url;
            $country->save();
            return response()->json(['success' => true, 'data' => $country]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getCountry($id)
    {

        $country = Country::where('id', $id)->with('eduSystems')->first();
        return response()->json(['success' => true, 'data' => $country]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateCountry(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('countries')->ignore($id),
            // 'key' => 'required', Rule::unique('countries')->ignore($id),
        ]);
        if ($validator->passes()) {
            $country = Country::findorfail($id);

            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path('storage/flags'), $name);
            $url =  env('APP_URL') . '/storage/flags/' . $name;

            $country->update($request->all());

            $country->image = $url;
            $country->save();
            return response()->json(['success' => true, 'data' => $country]);
        }
        return response(['success' => $request->all(), 'errors' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCountry($id)
    {
        $country = Country::findorfail($id);
        $country->delete();
        return response()->json(['success' => true, 'data' => $country]);
    }

    public function getEduSystems($country)
    {
        $EduSystem = EduSystem::where('country_id', $country)->with('country')->get();
        return response()->json(['success' => true, 'data' => $EduSystem]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveEduSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'country_id' => 'required|exists:countries,id'
        ]);
        if ($validator->passes()) {
            $EduSystem = new EduSystem();
            $EduSystem = $EduSystem->fill($request->all());
            $EduSystem->save();
            return response()->json(['success' => true, 'data' => $EduSystem]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getEduSystem($id)
    {
        $EduSystem = EduSystem::where('id', $id)->with('country')->first();
        return response()->json(['success' => true, 'data' => $EduSystem]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateEduSystem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('edu_systems')->ignore($id),
            'country_id' => 'required|exists:countries,id'
        ]);
        if ($validator->passes()) {
            $EduSystem = EduSystem::where('id', $id)->with('country')->first();
            $EduSystem->update($request->all());
            return response()->json(['success' => true, 'data' => $EduSystem]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteEduSystem($id)
    {
        $EduSystem = EduSystem::findorfail($id);
        $EduSystem->delete();
        return response()->json(['success' => true, 'data' => $EduSystem]);
    }

    public function getEduStages($eduSystem)
    {
        $EduStage = EduStage::where('edu_system_id', $eduSystem)->with(['system', 'system.country', 'semesters'])->get();

        return response()->json(['success' => true, 'data' => $EduStage]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveEduStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:edu_stages',
            'edu_system_id' => 'required|exists:edu_systems,id'
        ]);
        if ($validator->passes()) {
            $EduStage = new EduStage();
            $EduStage = $EduStage->fill($request->all());
            $EduStage->save();
            return response()->json(['success' => true, 'data' => $EduStage]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getEduStage($id)
    {
        $EduStage = EduStage::where('id', $id)->with(['system', 'system.country'])->first();


        return response()->json(['success' => true, 'data' => $EduStage]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateEduStage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('edu_stages')->ignore($id),
            'edu_system_id' => 'required|exists:edu_systems,id'
        ]);
        if ($validator->passes()) {
            $EduStage = EduStage::where('id', $id)->with(['system', 'system.country'])->first();
            $EduStage->update($request->all());
            return response()->json(['success' => true, 'data' => $EduStage]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    public function assignSemesters(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'edu_stage_id' => 'required|exists:edu_stages,id',
            'semesters' => 'required',
        ]);
        if ($validator->passes()) {
            $EduStage = EduStage::where('id', $request->edu_stage_id)->with(['system', 'system.country', 'semesters'])->first();

            $EduStage->semesters()->attach($request->semesters);

            return response()->json(['success' => true, 'data' => $EduStage]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }
    public function removeSemestersFromStage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edu_stage_id' => 'required|exists:edu_stages,id',
            'semesters' => 'required',
        ]);
        if ($validator->passes()) {
            $EduStage = EduStage::where('id', $request->edu_stage_id)->with(['system', 'system.country', 'semesters'])->first();

            $EduStage->semesters()->detach($request->semesters);

            return response()->json(['success' => true, 'data' => $EduStage]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteEduStage($id)
    {
        $EduStage = EduStage::findorfail($id);
        $EduStage->delete();
        return response()->json(['success' => true, 'data' => $EduStage]);
    }

    public function getSemesters()
    {


        $Semester = Semester::with(['eduStages', 'eduStages.system', 'eduStages.system.country'])->get();
        return response()->json($Semester);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveSemester(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:subjects',
        ]);
        if ($validator->passes()) {
            $semester = new Semester();
            $semester = $semester->fill($request->all());
            $semester->save();
            return response()->json(['success' => true, 'data' => $semester]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getSemester($id)
    {
        $Semester = Semester::where('id', $id)->with(['eduStages', 'eduStages.system', 'eduStages.system.country'])->first();
        return response()->json($Semester);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateSemester(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('subjects')->ignore($id),
        ]);
        if ($validator->passes()) {

            $semester = Semester::where('id', $id)->with(['eduStages', 'eduStages.system', 'eduStages.system.country'])->first();
            $semester->update($request->all());
            return response()->json(['success' => true, 'data' => $semester]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSemester($id)
    {
        $semester = Semester::findorfail($id);
        $semester->delete();
        return response()->json(['success' => true, 'data' => $semester]);
    }



    public function getSubjects()
    {
        $subject = Subject::get();
        return response()->json($subject);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:subjects',
        ]);
        if ($validator->passes()) {
            $subject = new Subject();
            $subject = $subject->fill($request->all());
            $subject->save();
            return response()->json(['success' => true, 'data' => $subject]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getSubject($id)
    {
        $subject = Subject::where('id', $id)->first();
        return response()->json($subject);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateSubject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('subjects')->ignore($id),
        ]);
        if ($validator->passes()) {

            $subject = Subject::where('id', $id)->first();
            $subject->update($request->all());
            return response()->json(['success' => true, 'data' => $subject]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSubject($id)
    {
        $subject = Subject::findorfail($id);
        $subject->delete();
        return response()->json(['success' => true, 'data' => $subject]);
    }

    public function addToWishlist(Request $request)
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

        return response()->json(['msg' => $msg]);
    }

    public function getMyWishlist()
    {

        $courses = auth()->user()->wishList;

        return response()->json(["courses" => $courses]);
    }

    public function removeFromWishlist(Request $request)
    {

        $course_id = $request->course_id;
        $wishlist = auth()->user()->wishList->where('id', $course_id)->first();
        $wishlist->pivot->delete();
        return response()->json(['msg' => "Item Removed"]);
    }

    public function getYears()
    {
        $years = Year::get();
        return response()->json($years);
    }

    public function getYear($id)
    {
        $year = Year::where('id', $id)->first();
        return response()->json($year);
    }
    public function saveYear(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|unique:subjects',
        ]);
        if ($validator->passes()) {
            $year = new Year();
            $year = $year->fill($request->all());
            $year->save();
            return response()->json(['success' => true, 'data' => $year]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }

    public function deleteYear($id)
    {
        $year = Year::findorfail($id);
        $year->delete();
        return response()->json(['success' => true, 'data' => $year]);
    }
    public function updateYear(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required', Rule::unique('subjects')->ignore($id),
        ]);
        if ($validator->passes()) {

            $year = Year::where('id', $id)->first();
            $year->update($request->all());
            return response()->json(['success' => true, 'data' => $year]);
        }
        return response(['success' => false, 'errors' => $validator->errors()]);
    }
    private function totalPriceOfUserCart($userId)
    {
        $user = User::findOrFail($userId);
        $cart = StudentCart::where('user_id', $user->id)->get();
        $totalPrice = 0;
        foreach ($cart as $rec => $record) {

            if ($record['item_type'] == 'course') {
                $item = Course::findOrFail($record['item_id']);
            } elseif ($record['item_type'] == 'bundle') {
                $item = Bundle::findOrFail($record['item_id']);
            }
            $totalPrice += $item->price;
        }
        return $totalPrice;
    }
    private function test()
    {
        $merchantCode    = '1tSa6uxz2nRlhbmxHHde5A==';
        $merchantRefNum  = '99900642041';
        $merchant_cust_prof_id  = '458626698';
        $payment_method = 'PAYATFAWRY';
        $amount = '580.55';
        $merchant_sec_key =  '259af31fc2f74453b3a55739b21ae9ef'; // For the sake of demonstration
        $signature = hash('sha256', $merchantCode . $merchantRefNum . $merchant_cust_prof_id . $payment_method . $amount . $merchant_sec_key);
        $httpClient = new \GuzzleHttp\Client(); // guzzle 6.3
        $response = $httpClient->request('POST', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ],
            'body' => json_encode([
                'merchantCode' => $merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'customerName' => 'Ahmed Ali',
                'customerMobile' => '01234567891',
                'customerEmail' => 'example@gmail.com',
                'customerProfileId' => '777777',
                'amount' => '580.55',
                'paymentExpiry' => 1631138400000,
                'currencyCode' => 'EGP',
                'language' => 'en-gb',
                'chargeItems' => [
                    'itemId' => '897fa8e81be26df25db592e81c31c',
                    'description' => 'Item Description',
                    'price' => '580.55',
                    'quantity' => '1'
                ],
                'signature' => $signature,
                'payment_method' => $payment_method,
                'description' => 'example description'
            ], true)
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        $paymentStatus = $response['type']; // get response values
        dd($response);
    }
    public function fawryPayment()
    {
        $this->test();
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $amount = $this->totalPriceOfUserCart($userId);
        if (strpos($amount, '.') !== false) {
            $amount = round($amount, 2);
        } else {
            $amount = $amount . '.00';
        }
        // dd($amount) ; 
        $fawryUrl = 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge';
        $merchantCode = config('fawry.merchant_code');
        // dd($merchantCode) ; 
        $customerProfileId = auth()->user()->id;
        $paymentMethod = 'PAYATFAWRY';
        $secureKey = config('fawry.security_key');
        // dd($secureKey) ; 
        $merchantRefNum = str_random(8);

        $buffer = $merchantCode . $merchantRefNum . $customerProfileId . $paymentMethod . $amount . $secureKey;
        $signature = hash('sha256', $buffer);

        // $fawryData = [
        //     'merchantCode' => $merchantCode,
        //     'merchantRefNum' => $merchantRefNum,
        //     'paymentMethod' => $paymentMethod,
        //     'customerMobile' => '01149786203',
        //     'customerEmail'=>'mohamedalmograby@gmail.com',
        //     'amount' => $amount,
        //     'paymentExpiry'=>'2021-09-08T10:00:00.100Z',
        //     'description'=> 'description' , 
        //     'language' =>"en-gb" , 
        //     'customerProfileId' => $customerProfileId,
        //     'customerName'=>'mohamed almograby',
        //     'chargeItems' => [  
        //             'itemId'  =>  "897fa8e81be26df25db592e81c31c",
        //             'description'  =>  'description',
        //             'price'  =>  $amount,
        //             'quantity'  =>  1
        //     ], 
        //     // 'chargeItems'=> $invoiceDataArray,
        //     'signature' => $signature,
        // ];

        $fawryData = [
            'merchantCode' => '1tSa6uxz2nTwlaAmt38enA==',
            'merchantRefNum' => "2312465464",
            'paymentMethod' => 'PAYATFAWRY',
            'customerMobile' => '01149786203',
            'customerEmail' => 'mohamedalmograby@gmail.com',
            'amount' => '580.55',
            'paymentExpiry' => '2021-09-08T10:00:00.100Z',
            'description' => 'description',
            'language' => "en-gb",
            'customerProfileId' => "1",
            'customerName' => 'mohamed almograby',
            'chargeItems' => [
                'itemId'  =>  "897fa8e81be26df25db592e81c31c",
                'description'  =>  'description',
                'price'  =>  "580.55",
                'quantity'  =>  "1",
            ],
            // 'chargeItems'=> $invoiceDataArray,
            'signature' => '2ca4c078ab0d4c50ba90e31b3b0339d4d4ae5b32f97092dd9e9c07888c7eef36',
            "description" => "Example Description"
        ];
        $data_string = json_encode($fawryData);
        $ch = curl_init($fawryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        dd($result);
    }


















    public function getPackages()
    {
        $packages = Package::get();
        return response()->json(['success' => true, 'data' => $packages]);
    }

    public function getPackage($id)
    {
        $Package = Package::where('id', $id)->first();
        return response()->json(['success' => true, 'data' => $Package]);
    }
    public function savePackage(Request $request)
    {



        $package = new Package();
        $package = $package->fill($request->all());
        $package->save();
        return response()->json(['success' => true, 'data' => $package]);
    }

    public function deletePackage($id)
    {
        $package = Package::findorfail($id);
        $package->delete();
        return response()->json(['success' => true, 'data' => $package]);
    }
    public function updatePackage(Request $request, $id)
    {


        $package = Package::where('id', $id)->first();
        $package->update($request->all());
        return response()->json(['success' => true, 'data' => $package]);
    }
}
