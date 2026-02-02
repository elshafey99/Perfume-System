<?php

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSaleRequest extends FormRequest
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
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:amount,percentage'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['required', 'in:cash,card,bank_transfer,apple_pay,split'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'sale_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id', 'required_without:items.*.composition_id'],
            'items.*.composition_id' => ['nullable', 'integer', 'exists:compositions,id', 'required_without:items.*.product_id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit' => ['required', 'in:piece,gram,ml,tola,quarter_tola'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.custom_price' => ['nullable', 'numeric', 'min:0'], // For open_price products
            'items.*.is_custom_blend' => ['nullable', 'boolean'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'tax_rate' => $this->input('tax_rate', 15), // Default VAT 15%
            'discount' => $this->input('discount', 0),
            'paid_amount' => $this->input('paid_amount', 0),
        ]);

        // Convert boolean fields in items
        if ($this->has('items')) {
            $items = $this->input('items');
            foreach ($items as $key => $item) {
                if (isset($item['is_custom_blend'])) {
                    $items[$key]['is_custom_blend'] = filter_var($item['is_custom_blend'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $item['is_custom_blend'];
                }
            }
            $this->merge(['items' => $items]);
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
            'customer_id.exists' => __('validation.exists', ['attribute' => 'العميل']),
            'payment_method.required' => __('validation.required', ['attribute' => 'طريقة الدفع']),
            'payment_method.in' => __('validation.in', ['attribute' => 'طريقة الدفع']),
            'items.required' => __('validation.required', ['attribute' => 'المنتجات']),
            'items.min' => 'يجب إضافة منتج واحد على الأقل',
            'items.*.product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'items.*.composition_id.exists' => __('validation.exists', ['attribute' => 'التركيبة']),
            'items.*.quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'items.*.quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'items.*.unit.required' => __('validation.required', ['attribute' => 'الوحدة']),
            'items.*.unit.in' => __('validation.in', ['attribute' => 'الوحدة']),
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
