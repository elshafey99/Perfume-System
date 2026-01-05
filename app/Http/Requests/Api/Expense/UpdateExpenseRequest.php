<?php

namespace App\Http\Requests\Api\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateExpenseRequest extends FormRequest
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
            'category' => ['sometimes', 'required', 'string', 'in:rent,salaries,electricity,shipping,marketing,maintenance,other'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'expense_date' => ['sometimes', 'required', 'date'],
            'receipt_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
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
            'category.required' => __('validation.required', ['attribute' => 'الفئة']),
            'category.in' => __('validation.in', ['attribute' => 'الفئة']),
            'amount.required' => __('validation.required', ['attribute' => 'المبلغ']),
            'amount.numeric' => __('validation.numeric', ['attribute' => 'المبلغ']),
            'amount.min' => __('validation.min.numeric', ['attribute' => 'المبلغ', 'min' => 0]),
            'expense_date.required' => __('validation.required', ['attribute' => 'تاريخ المصروف']),
            'expense_date.date' => __('validation.date', ['attribute' => 'تاريخ المصروف']),
            'description.string' => __('validation.string', ['attribute' => 'الوصف']),
            'receipt_image.image' => __('validation.image', ['attribute' => 'صورة الإيصال']),
            'receipt_image.mimes' => __('validation.mimes', ['attribute' => 'صورة الإيصال', 'values' => 'jpg, jpeg, png, gif, webp']),
            'receipt_image.max' => __('validation.max.file', ['attribute' => 'صورة الإيصال', 'max' => '5 MB']),
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
