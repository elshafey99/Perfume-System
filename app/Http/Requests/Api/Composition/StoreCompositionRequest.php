<?php

namespace App\Http\Requests\Api\Composition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCompositionRequest extends FormRequest
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
            'code' => ['nullable', 'string', 'max:100', 'unique:compositions,code'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'bottle_size' => ['required', 'numeric', 'min:0'],
            'concentration_type' => ['nullable', 'string', 'in:EDP,EDT,Parfum,Cologne'],
            'base_cost' => ['nullable', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_magic_recipe' => ['nullable', 'boolean'],
            'original_perfume_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean for form-data
        $booleanFields = ['is_magic_recipe', 'is_active'];
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
            'name.required' => __('validation.required', ['attribute' => 'اسم التركيبة']),
            'name.string' => __('validation.string', ['attribute' => 'اسم التركيبة']),
            'name.max' => __('validation.max.string', ['attribute' => 'اسم التركيبة', 'max' => 255]),
            'code.unique' => __('validation.unique', ['attribute' => 'رمز التركيبة']),
            'product_id.exists' => __('validation.exists', ['attribute' => 'المنتج']),
            'bottle_size.required' => __('validation.required', ['attribute' => 'حجم الزجاجة']),
            'bottle_size.numeric' => __('validation.numeric', ['attribute' => 'حجم الزجاجة']),
            'bottle_size.min' => __('validation.min.numeric', ['attribute' => 'حجم الزجاجة', 'min' => 0]),
            'concentration_type.in' => __('validation.in', ['attribute' => 'نوع التركيز']),
            'image.image' => __('validation.image', ['attribute' => 'الصورة']),
            'image.mimes' => __('validation.mimes', ['attribute' => 'الصورة', 'values' => 'jpeg,png,jpg,gif,webp']),
            'image.max' => __('validation.max.file', ['attribute' => 'الصورة', 'max' => 5120]),
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

