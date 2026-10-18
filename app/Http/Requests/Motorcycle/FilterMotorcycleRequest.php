<?php

namespace App\Http\Requests\Motorcycle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'price_min' => ['nullable', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'min:0', 
                Rule::when($this->filled('price_min'), 'gte:price_min')],
            'engine_cc_min' => ['nullable', 'integer', 'min:0'],
            'engine_cc_max' => ['nullable', 'integer', 'min:0', 
                Rule::when($this->filled('engine_cc_min'), 'gte:engine_cc_min')],
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc'],
        ];
    }
}
