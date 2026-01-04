<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangeStatusRequest extends FormRequest
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
            'status' => ['required', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Don't convert before validation - let validation check the original value
        // We'll convert after validation passes
    }

    /**
     * Get validated data and convert status to boolean
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Convert status to boolean after validation passes
        if (isset($validated['status'])) {
            $status = $validated['status'];

            if (is_string($status)) {
                $validated['status'] = in_array(strtolower($status), ['true', '1', 'yes', 'on'], true);
            } elseif (is_numeric($status)) {
                $validated['status'] = (bool) (int) $status;
            } else {
                $validated['status'] = (bool) $status;
            }
        }

        return $key ? ($validated[$key] ?? $default) : $validated;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => __('validation.required', ['attribute' => 'الحالة']),
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
