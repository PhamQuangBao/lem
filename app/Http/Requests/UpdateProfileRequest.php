<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'mail' => 'required|email|max:255',
            'phone_number' => 'required|regex:/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/|min:3',
            'year_of_experience' => 'required|integer',
            'submit_date' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone_number.regex' => 'The phone number has to be in either national or international format.',
        ];
    }
}
