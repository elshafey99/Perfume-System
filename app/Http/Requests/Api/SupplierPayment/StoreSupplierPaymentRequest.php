<?php

namespace App\Http\Requests\Api\SupplierPayment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSupplierPaymentRequest extends FormRequest
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
            'type' => ['required', 'in:payment,opening_balance,refund'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['nullable', 'in:cash,card,bank_transfer,cheque'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'نوع الدفعة مطلوب',
            'type.in' => 'نوع الدفعة غير صحيح',
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
            'payment_date.required' => 'تاريخ الدفعة مطلوب',
            'payment_date.date' => 'تاريخ الدفعة غير صحيح',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
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
