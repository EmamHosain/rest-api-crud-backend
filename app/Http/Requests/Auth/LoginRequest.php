<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'email.required' => 'Email should be required',
            'email.string' => 'Email should be string',
            'email.email' => 'Email should be email',
            'password.required' => 'Password should be required',
            'password.string' => 'Password should be string',
        ];
    }
}
