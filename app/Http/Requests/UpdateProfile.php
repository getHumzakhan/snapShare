<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            "name" => 'not_regex:/[#@$%^&*\/()_=+!<>;"{}\'?]/i|not_regex:/[0-9]/i',
            "email" => 'email:filter',
            "age" => 'gt:8|lt:100',
            "image" => 'mimes:jpg,png,gif,jpeg',
        ];
    }
}
