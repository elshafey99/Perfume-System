<?php

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompositionSaleRequest extends FormRequest
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
            'composition_id' => ['required', 'integer', 'exists:compositions,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['nullable', 'in:piece,gram,ml,tola,quarter_tola'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,card,bank_transfer,apple_pay,split'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:amount,percentage'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'composition_id.required' => __('validation.required', ['attribute' => 'التركيبة']),
            'composition_id.exists' => __('validation.exists', ['attribute' => 'التركيبة']),
            'quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'payment_method.required' => __('validation.required', ['attribute' => 'طريقة الدفع']),
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
