<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPass extends FormRequest
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
            'password' => 'required|min:8|alpha_dash|confirmed',
            'user_id' => 'required',
            'token' => 'required'
        ];
    }
}
