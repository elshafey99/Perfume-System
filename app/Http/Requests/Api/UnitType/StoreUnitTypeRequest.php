<?php

namespace App\Http\Requests\Api\UnitType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUnitTypeRequest extends FormRequest
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
            'code' => ['nullable', 'string', 'max:255', 'unique:unit_types,code'],
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean for form-data
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->input('is_active'),
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
            'code.string' => __('validation.string', ['attribute' => 'الكود']),
            'code.max' => __('validation.max.string', ['attribute' => 'الكود', 'max' => 255]),
            'code.unique' => __('validation.unique', ['attribute' => 'الكود']),
            'name.required' => __('validation.required', ['attribute' => 'الاسم']),
            'name.string' => __('validation.string', ['attribute' => 'الاسم']),
            'name.max' => __('validation.max.string', ['attribute' => 'الاسم', 'max' => 255]),
            'symbol.string' => __('validation.string', ['attribute' => 'الرمز']),
            'symbol.max' => __('validation.max.string', ['attribute' => 'الرمز', 'max' => 255]),
            'sort_order.integer' => __('validation.integer', ['attribute' => 'ترتيب العرض']),
            'sort_order.min' => __('validation.min.numeric', ['attribute' => 'ترتيب العرض', 'min' => 0]),
            'is_active.in' => __('validation.boolean', ['attribute' => 'الحالة']),
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

