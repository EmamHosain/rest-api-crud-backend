<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_admin' => ['integer', 'nullable'],
            'email' => ['required', 'string', 'email'],
            'name' => ['required', 'string', 'max:50'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }


    public function messages(): array
    {
        return [
            'email.required' => 'Email must be required',
            'email.string' => 'Email must be type of string',
            'email.email' => 'Email must be type of email',

            'name.required' => 'Name must be required',
            'name.string' => 'Name must type of string',
            'name.max' => 'Name must be maximum 50 characters',

            'password.required' => 'Password must be required',
            'password.min' => 'Password must be minimum 8 characters',
            'password.confirmed' => 'Password and confirm password must be same',



        ];
    }
}
