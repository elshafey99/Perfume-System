<?php

namespace App\Http\Requests\Api\Composition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCompositionIngredientRequest extends FormRequest
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
            'ingredient_product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'unit' => ['required', 'string', 'in:piece,gram,ml'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
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
            'ingredient_product_id.required' => __('validation.required', ['attribute' => 'المنتج المكون']),
            'ingredient_product_id.exists' => __('validation.exists', ['attribute' => 'المنتج المكون']),
            'quantity.required' => __('validation.required', ['attribute' => 'الكمية']),
            'quantity.numeric' => __('validation.numeric', ['attribute' => 'الكمية']),
            'quantity.min' => __('validation.min.numeric', ['attribute' => 'الكمية', 'min' => 0.0001]),
            'unit.required' => __('validation.required', ['attribute' => 'وحدة القياس']),
            'unit.in' => __('validation.in', ['attribute' => 'وحدة القياس']),
            'sort_order.integer' => __('validation.integer', ['attribute' => 'ترتيب العرض']),
            'sort_order.min' => __('validation.min.numeric', ['attribute' => 'ترتيب العرض', 'min' => 0]),
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

