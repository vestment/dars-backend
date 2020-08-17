<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use Illuminate\Support\Facades\Session;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;


/**
 * Class ContactController.
 */
class ContactController extends Controller
{

    private $path;

    public function __construct()
    {
        $this->path = 'frontend';
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->path.'.contact');
    }

    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function send(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha')
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->number = $request->phone;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->save();

        Mail::send(new SendContact($request));
        Session::flash('alert','Response received successfully!');



        return redirect()->back();
    }
}
