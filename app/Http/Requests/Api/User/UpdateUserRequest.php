<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $userId],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'type' => ['sometimes', 'required', 'in:admin,employee'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
            'position' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:true,false,1,0,"1","0"'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean for form-data
        if ($this->has('status')) {
            $this->merge([
                'status' => filter_var($this->input('status'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->input('status'),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'الاسم']),
            'name.string' => __('validation.string', ['attribute' => 'الاسم']),
            'name.max' => __('validation.max.string', ['attribute' => 'الاسم', 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => 'البريد الإلكتروني']),
            'email.email' => __('validation.email', ['attribute' => 'البريد الإلكتروني']),
            'email.max' => __('validation.max.string', ['attribute' => 'البريد الإلكتروني', 'max' => 255]),
            'email.unique' => __('validation.unique', ['attribute' => 'البريد الإلكتروني']),
            'password.string' => __('validation.string', ['attribute' => 'كلمة المرور']),
            'password.min' => __('validation.min.string', ['attribute' => 'كلمة المرور', 'min' => 8]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => 'كلمة المرور']),
            'phone.string' => __('validation.string', ['attribute' => 'رقم الهاتف']),
            'phone.max' => __('validation.max.string', ['attribute' => 'رقم الهاتف', 'max' => 20]),
            'phone.unique' => __('validation.unique', ['attribute' => 'رقم الهاتف']),
            'image.image' => __('validation.image', ['attribute' => 'الصورة']),
            'image.mimes' => __('validation.mimes', ['attribute' => 'الصورة', 'values' => 'jpeg,png,jpg,gif,webp']),
            'image.max' => __('validation.max.file', ['attribute' => 'الصورة', 'max' => 2048]),
            'type.required' => __('validation.required', ['attribute' => 'النوع']),
            'type.in' => __('validation.in', ['attribute' => 'النوع']),
            'role_id.integer' => __('validation.integer', ['attribute' => 'الدور']),
            'role_id.exists' => __('validation.exists', ['attribute' => 'الدور']),
            'position.string' => __('validation.string', ['attribute' => 'المنصب']),
            'position.max' => __('validation.max.string', ['attribute' => 'المنصب', 'max' => 255]),
            'status.in' => __('validation.in', ['attribute' => 'الحالة']),
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

