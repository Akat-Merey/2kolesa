<?php

namespace App\Http\Requests\Motorcycle;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
