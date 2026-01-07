<?php

namespace App\Http\Requests\Api\Return;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
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
            'sale_id' => 'required|exists:sales,id',
            'sale_item_id' => 'nullable|exists:sale_items,id',
            'return_reason' => 'required|in:defective,wrong_item,customer_request,other',
            'return_type' => 'required|in:refund,exchange,store_credit',
            'return_amount' => 'nullable|numeric|min:0.01',
            'notes' => 'nullable|string|max:1000',
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
            'sale_id.required' => __('returns.validation.sale_id_required'),
            'sale_id.exists' => __('returns.validation.sale_id_exists'),
            'sale_item_id.exists' => __('returns.validation.sale_item_id_exists'),
            'return_reason.required' => __('returns.validation.return_reason_required'),
            'return_reason.in' => __('returns.validation.return_reason_in'),
            'return_type.required' => __('returns.validation.return_type_required'),
            'return_type.in' => __('returns.validation.return_type_in'),
            'return_amount.required' => __('returns.validation.return_amount_required'),
            'return_amount.numeric' => __('returns.validation.return_amount_numeric'),
            'return_amount.min' => __('returns.validation.return_amount_min'),
            'notes.max' => __('returns.validation.notes_max'),
        ];
    }
}
