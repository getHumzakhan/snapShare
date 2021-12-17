<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPost extends FormRequest
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
            'data.creation_date' => 'date_format:y-m-d|string',
            'data.creation_time' => 'date_format:h:i:s|string',
            'data.name' => 'Alpha',
            'data.ext' => 'in:jpg,jpeg,png|string|not_regex:/[A-Z+]/',
            'data.privacy' => 'in:public,private,hidden|string|not_regex:/[A-Z+]/' 
        ];
    }

    public function messages(){
        return [
            'data.ext.in' => 'Only jpg, jpeg or png formats allowed',
            'data.ext.not_regex' => 'All leters must be lowercase',
            'data.privacy.in' => 'Values Allowed: public / private / hidden',
            'data.privacy.not_regex' => 'All leters must be lowercase'
        ];
    }
}
