<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'product_type_id' => ['required', 'integer', 'exists:product_types,id'],
            'unit_type_id' => ['required', 'integer', 'exists:unit_types,id'],
            'conversion_rate' => ['nullable', 'numeric', 'min:0'],
            'current_stock' => ['nullable', 'numeric', 'min:0'],
            'min_stock_level' => ['nullable', 'numeric', 'min:0'],
            'max_stock_level' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'price_per_gram' => ['nullable', 'numeric', 'min:0'],
            'price_per_ml' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'is_raw_material' => ['nullable', 'boolean'],
            'is_composition' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'can_return' => ['nullable', 'boolean'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean for form-data
        $booleanFields = ['is_raw_material', 'is_composition', 'is_active', 'can_return'];
        foreach ($booleanFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => filter_var($this->input($field), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->input($field),
                ]);
            }
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
            'name.required' => __('validation.required', ['attribute' => 'اسم المنتج']),
            'name.string' => __('validation.string', ['attribute' => 'اسم المنتج']),
            'name.max' => __('validation.max.string', ['attribute' => 'اسم المنتج', 'max' => 255]),
            'sku.unique' => __('validation.unique', ['attribute' => 'رمز المنتج']),
            'barcode.unique' => __('validation.unique', ['attribute' => 'الباركود']),
            'category_id.required' => __('validation.required', ['attribute' => 'الفئة']),
            'category_id.exists' => __('validation.exists', ['attribute' => 'الفئة']),
            'product_type_id.required' => __('validation.required', ['attribute' => 'نوع المنتج']),
            'product_type_id.exists' => __('validation.exists', ['attribute' => 'نوع المنتج']),
            'unit_type_id.required' => __('validation.required', ['attribute' => 'وحدة القياس']),
            'unit_type_id.exists' => __('validation.exists', ['attribute' => 'وحدة القياس']),
            'image.image' => __('validation.image', ['attribute' => 'الصورة']),
            'image.mimes' => __('validation.mimes', ['attribute' => 'الصورة', 'values' => 'jpeg,png,jpg,gif,webp']),
            'image.max' => __('validation.max.file', ['attribute' => 'الصورة', 'max' => 5120]),
            'supplier_id.exists' => __('validation.exists', ['attribute' => 'المورد']),
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

