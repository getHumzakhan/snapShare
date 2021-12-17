<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrivacy extends FormRequest
{
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
            'privacy' => 'required|in:public,private,hidden|string|not_regex:/[A-Z+]/'
        ];
    }

    public function messages(){
        return [
            'privacy.in' => 'Values Allowed: public / private / hidden',
            'privacy.not_regex' => 'All leters must be lowercase'
        ];
    }
}
