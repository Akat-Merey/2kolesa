<?php

namespace App\Http\Requests\Motorcycle;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (now()->year + 1)],
            'price' => ['required', 'integer', 'min:1'],
            'engine_cc' => ['required', 'integer', 'min:50', 'max:3000'],
            'mileage' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
