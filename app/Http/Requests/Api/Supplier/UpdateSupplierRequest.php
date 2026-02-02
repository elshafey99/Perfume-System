<?php

namespace App\Http\Requests\Api\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSupplierRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phones' => ['nullable', 'array'],
            'phones.*' => ['string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'area' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
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
            'name.required' => __('validation.required', ['attribute' => 'اسم المورد']),
            'name.string' => __('validation.string', ['attribute' => 'اسم المورد']),
            'name.max' => __('validation.max.string', ['attribute' => 'اسم المورد', 'max' => 255]),
            'contact_person.string' => __('validation.string', ['attribute' => 'الشخص المسؤول']),
            'contact_person.max' => __('validation.max.string', ['attribute' => 'الشخص المسؤول', 'max' => 255]),
            'phone.string' => __('validation.string', ['attribute' => 'رقم الهاتف']),
            'phone.max' => __('validation.max.string', ['attribute' => 'رقم الهاتف', 'max' => 50]),
            'email.email' => __('validation.email', ['attribute' => 'البريد الإلكتروني']),
            'email.max' => __('validation.max.string', ['attribute' => 'البريد الإلكتروني', 'max' => 255]),
            'address.string' => __('validation.string', ['attribute' => 'العنوان']),
            'tax_number.string' => __('validation.string', ['attribute' => 'الرقم الضريبي']),
            'tax_number.max' => __('validation.max.string', ['attribute' => 'الرقم الضريبي', 'max' => 100]),
            'notes.string' => __('validation.string', ['attribute' => 'الملاحظات']),
            'is_active.boolean' => __('validation.boolean', ['attribute' => 'الحالة']),
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

