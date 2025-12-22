<?php

namespace App\Http\Requests\Api\Stocktaking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStocktakingItemRequest extends FormRequest
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
            'actual_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'in:piece,gram,ml,tola,quarter_tola'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
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
            'actual_stock.required' => __('validation.required', ['attribute' => 'المخزون الفعلي']),
            'actual_stock.numeric' => __('validation.numeric', ['attribute' => 'المخزون الفعلي']),
            'actual_stock.min' => __('validation.min.numeric', ['attribute' => 'المخزون الفعلي', 'min' => 0]),
            'unit.in' => __('validation.in', ['attribute' => 'وحدة القياس']),
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

