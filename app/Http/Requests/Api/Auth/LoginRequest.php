<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:2'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => __('validation.required', ['attribute' => 'اسم المستخدم']),
            'username.string' => __('validation.string', ['attribute' => 'اسم المستخدم']),
            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('auth.password'), 'min' => 2]),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'status' => 422,
                'message' => __('api.validation_failed'),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
