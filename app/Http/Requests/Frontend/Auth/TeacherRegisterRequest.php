<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TeacherRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'           => ['required', 'string', 'max:191'],
            'last_name'           => ['required', 'string', 'max:191'],
            'email'               => ['required', 'string', 'email', 'max:191', Rule::unique('users')],
            'password'            => ['required', 'string', 'min:6', 'confirmed'],
            'gender'              => ['required', 'in:male,female,other'],
            

            // 'g-recaptcha-response' => ['required_if:captcha_status,true', new CaptchaRule()],
        ];
    }
}
