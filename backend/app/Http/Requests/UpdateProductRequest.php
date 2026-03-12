<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name'        => 'sometimes|string|max:255',
            'product_description' => 'nullable|string',
            'product_type'        => 'sometimes|in:lata,bolsa,caja,botella',
            'unit_of_measurement' => 'sometimes|string',
            'net_content'         => 'sometimes|numeric|min:0',
        ];
    }
}
