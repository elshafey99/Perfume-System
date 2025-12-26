<?php

namespace App\Http\Requests\Api\Sale;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSaleItemRequest extends FormRequest
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
            'product_id' => ['nullable', 'integer', 'exists:products,id', 'required_without:composition_id'],
            'composition_id' => ['nullable', 'integer', 'exists:compositions,id', 'required_without:product_id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['required', 'in:piece,gram,ml,tola,quarter_tola'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'is_custom_blend' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_custom_blend')) {
            $this->merge([
                'is_custom_blend' => filter_var($this->input('is_custom_blend'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->input('is_custom_blend'),
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
            'product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'product_id.required_without' => 'يجب اختيار منتج أو تركيبة',
            'composition_id.exists' => __('validation.exists', ['attribute' => 'التركيبة']),
            'composition_id.required_without' => 'يجب اختيار منتج أو تركيبة',
            'quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'unit.required' => __('validation.required', ['attribute' => 'الوحدة']),
            'unit.in' => __('validation.in', ['attribute' => 'الوحدة']),
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
