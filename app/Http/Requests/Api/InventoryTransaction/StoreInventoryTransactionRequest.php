<?php

namespace App\Http\Requests\Api\InventoryTransaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInventoryTransactionRequest extends FormRequest
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
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'string', 'in:sale,purchase,return,adjustment,composition,waste'],
            'quantity' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (abs($value) < 0.0001) {
                    $fail('يجب أن تكون قيمة الكمية (القيمة المطلقة) مساوية أو أكبر من 0.0001.');
                }
            }],
            'unit' => ['nullable', 'string', 'in:piece,gram,ml,tola,quarter_tola'],
            'reference_type' => ['nullable', 'string'],
            'reference_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
            'transaction_date' => ['nullable', 'date'],
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
            'product_id.required' => __('validation.required', ['attribute' => 'المنتج']),
            'product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'type.required' => __('validation.required', ['attribute' => 'نوع العملية']),
            'type.in' => __('validation.in', ['attribute' => 'نوع العملية']),
            'quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'quantity.numeric' => __('validation.numeric', ['attribute' => 'الكمية']),
            'unit.in' => __('validation.in', ['attribute' => 'وحدة القياس']),
            'transaction_date.date' => __('validation.date', ['attribute' => 'تاريخ العملية']),
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

