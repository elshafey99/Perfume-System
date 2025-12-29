<?php

namespace App\Http\Requests\Api\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePurchaseItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['nullable', 'in:piece,gram,ml,tola,quarter_tola'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => __('validation.required', ['attribute' => 'المنتج']),
            'product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
        ];
    }

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
