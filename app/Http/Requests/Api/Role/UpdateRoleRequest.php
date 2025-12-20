<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRoleRequest extends FormRequest
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
        $availablePermissions = array_keys(config('permessions_ar', []));

        return [
            'role' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'string', 'in:' . implode(',', $availablePermissions)],
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
            'role.required' => __('validation.required', ['attribute' => 'اسم الدور']),
            'role.string' => __('validation.string', ['attribute' => 'اسم الدور']),
            'role.max' => __('validation.max.string', ['attribute' => 'اسم الدور', 'max' => 255]),
            'permissions.required' => __('validation.required', ['attribute' => 'الصلاحيات']),
            'permissions.array' => __('validation.array', ['attribute' => 'الصلاحيات']),
            'permissions.min' => __('validation.min.array', ['attribute' => 'الصلاحيات', 'min' => 1]),
            'permissions.*.required' => __('validation.required', ['attribute' => 'الصلاحية']),
            'permissions.*.string' => __('validation.string', ['attribute' => 'الصلاحية']),
            'permissions.*.in' => __('validation.in', ['attribute' => 'الصلاحية']),
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

