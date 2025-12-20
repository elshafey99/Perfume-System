<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $userId],
            'position' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
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
            'name.required' => __('validation.required', ['attribute' => __('auth.name')]),
            'name.string' => __('validation.string', ['attribute' => __('auth.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('auth.name'), 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => __('auth.email')]),
            'email.email' => __('validation.email', ['attribute' => __('auth.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('auth.email')]),
            'phone.string' => __('validation.string', ['attribute' => __('auth.phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('auth.phone'), 'max' => 20]),
            'phone.unique' => __('validation.unique', ['attribute' => __('auth.phone')]),
            'position.string' => __('validation.string', ['attribute' => 'المنصب']),
            'position.max' => __('validation.max.string', ['attribute' => 'المنصب', 'max' => 255]),
            'image.image' => __('validation.image', ['attribute' => 'الصورة']),
            'image.mimes' => __('validation.mimes', ['attribute' => 'الصورة', 'values' => 'jpeg,png,jpg,gif,webp']),
            'image.max' => __('validation.max.file', ['attribute' => 'الصورة', 'max' => 2048]),
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
