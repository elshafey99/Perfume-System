<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', 'different:' . $categoryId],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean for form-data
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $this->input('is_active'),
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
            'name.required' => __('validation.required', ['attribute' => 'اسم الفئة']),
            'name.string' => __('validation.string', ['attribute' => 'اسم الفئة']),
            'name.max' => __('validation.max.string', ['attribute' => 'اسم الفئة', 'max' => 255]),
            'parent_id.integer' => __('validation.integer', ['attribute' => 'الفئة الرئيسية']),
            'parent_id.exists' => __('validation.exists', ['attribute' => 'الفئة الرئيسية']),
            'parent_id.different' => __('validation.different', ['attribute' => 'الفئة الرئيسية']),
            'icon.image' => __('validation.image', ['attribute' => 'الأيقونة']),
            'icon.mimes' => __('validation.mimes', ['attribute' => 'الأيقونة', 'values' => 'jpeg,png,jpg,gif,webp,svg']),
            'icon.max' => __('validation.max.file', ['attribute' => 'الأيقونة', 'max' => 2048]),
            'description.string' => __('validation.string', ['attribute' => 'الوصف']),
            'sort_order.integer' => __('validation.integer', ['attribute' => 'ترتيب العرض']),
            'sort_order.min' => __('validation.min.numeric', ['attribute' => 'ترتيب العرض', 'min' => 0]),
            'is_active.boolean' => __('validation.boolean', ['attribute' => 'الحالة']),
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
