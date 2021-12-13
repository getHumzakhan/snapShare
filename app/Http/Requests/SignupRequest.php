<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
            "name" => 'required|not_regex:/[#@$%^&*\/()_=+!<>;"{}\'?]/i|not_regex:/[0-9]/i',
            "email" => 'required|email:filter',
            "password" => 'required|min:8|alpha_dash|confirmed',
            "age" => 'required|gt:8|lt:100',
            "image" => 'present',
        ];
    }

    public function messages()
    {
        return [
            'name.not_regex' => 'Special characters or integers not allowed in names',
        ];
    }
}
