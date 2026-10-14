<?php

namespace App\Http\Requests\Motorcycle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMotorcycleRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['sometimes', 'integer', 'exists:brands,id'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'city_id' => ['sometimes', 'integer', 'exists:cities,id'],
            'model' => ['sometimes', 'string', 'max:100'],
            'year' => ['sometimes', 'integer', 'min:1950', 'max:' . (now()->year + 1)],
            'price' => ['sometimes', 'integer', 'min:1'],
            'engine_cc' => ['sometimes', 'integer', 'min:50', 'max:3000'],
            'mileage' => ['sometimes', 'integer', 'min:0'],
            'description' => ['sometimes', 'string', 'max:5000'],
            'status' => ['sometimes', 'in:active,sold'],
        ];
    }
}
