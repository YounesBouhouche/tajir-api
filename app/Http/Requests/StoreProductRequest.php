<?php

namespace App\Http\Requests;

use App\Rules\ValidateProductAttributes;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required',
            'description' => 'required',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'type_id' => 'required|integer|exists:product_types,id',
            'attributes' => ['nullable|array', new ValidateProductAttributes($this->input('type_id'))],
        ];
    }

    public function messages()
    {
        return [
            'type_id.exists' => 'The selected category does not exist.',
            'attributes.array' => 'Attributes must be provided as an object.',
        ];
    }
}
