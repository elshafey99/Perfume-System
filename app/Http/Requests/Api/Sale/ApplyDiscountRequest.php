<?php

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplyDiscountRequest extends FormRequest
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
            'discount' => ['required', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:amount,percentage'],
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
            'discount.required' => __('validation.required', ['attribute' => 'الخصم']),
            'discount.min' => 'الخصم يجب أن يكون أكبر من أو يساوي صفر',
            'discount_type.required' => __('validation.required', ['attribute' => 'نوع الخصم']),
            'discount_type.in' => __('validation.in', ['attribute' => 'نوع الخصم']),
        ];
    }

    /**
     * Handle a failed validation attempt.
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
