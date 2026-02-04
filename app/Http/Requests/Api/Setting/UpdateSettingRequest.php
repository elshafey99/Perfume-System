<?php

namespace App\Http\Requests\Api\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSettingRequest extends FormRequest
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
            // Basic site info
            'site_name' => ['sometimes', 'string', 'max:255'],
            // 'site_desc' => ['sometimes', 'string'],
            'site_phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'site_address' => ['sometimes', 'string'],
            // 'site_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            // 'email_support' => ['sometimes', 'nullable', 'email', 'max:255'],
            
            // Social media
            // 'facebook' => ['sometimes', 'nullable', 'url', 'max:255'],
            // 'x_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            // 'youtube' => ['sometimes', 'nullable', 'url', 'max:255'],
            
            // SEO
            // 'meta_desc' => ['sometimes', 'nullable', 'string'],
            
            // Images
            'logo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
            'logo_receipt' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
            'favicon' => ['sometimes', 'nullable', 'image', 'mimes:ico,png', 'max:512'],
            
            // Other
            'site_copyright' => ['sometimes', 'nullable', 'string', 'max:255'],
            // 'promotion_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            // 'about_us' => ['sometimes', 'nullable', 'string'],
            
            // NEW POS Settings
            'default_tax_rate' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'default_discount_rate' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'receipt_thank_you_message' => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'default_tax_rate.numeric' => 'نسبة الضريبة يجب أن تكون رقماً',
            'default_tax_rate.min' => 'نسبة الضريبة يجب أن تكون أكبر من أو تساوي 0',
            'default_tax_rate.max' => 'نسبة الضريبة يجب أن تكون أقل من أو تساوي 100',
            'default_discount_rate.numeric' => 'نسبة الخصم يجب أن تكون رقماً',
            'default_discount_rate.min' => 'نسبة الخصم يجب أن تكون أكبر من أو تساوي 0',
            'default_discount_rate.max' => 'نسبة الخصم يجب أن تكون أقل من أو تساوي 100',
            'receipt_thank_you_message.max' => 'جملة الشكر يجب ألا تتجاوز 500 حرف',
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
