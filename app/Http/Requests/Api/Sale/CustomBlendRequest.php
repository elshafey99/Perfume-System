<?php

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomBlendRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'blend_name' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:cash,card,bank_transfer,apple_pay,split'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:amount,percentage'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'ingredients' => ['required', 'array', 'min:2'],
            'ingredients.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'ingredients.*.unit' => ['required', 'in:piece,gram,ml,tola,quarter_tola'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => __('validation.required', ['attribute' => 'طريقة الدفع']),
            'ingredients.required' => 'يجب إضافة مكونات الخلطة',
            'ingredients.min' => 'الخلطة المخصصة يجب أن تحتوي على مكونين على الأقل',
            'ingredients.*.product_id.required' => __('validation.required', ['attribute' => 'المنتج']),
            'ingredients.*.product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'ingredients.*.quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'ingredients.*.quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'ingredients.*.unit.required' => __('validation.required', ['attribute' => 'الوحدة']),
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
