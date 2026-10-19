<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ReferenceController extends Controller
{
    private const TTL = 3600;

    public function brands(): JsonResponse
    {
        $brands = Cache::remember('brands', self::TTL, fn () =>
            Brand::orderBy('name')->get(['id', 'name'])->toArray()  
        );

        return response()->json(['data' => $brands]);
    }

    public function categories(): JsonResponse
    {
        $categories = Cache::remember('categories', self::TTL, fn () =>
            Category::orderBy('name')->get(['id', 'name'])->toArray()
        );

        return response()->json(['data' => $categories]);
    }

    public function cities(): JsonResponse
    {
        $cities = Cache::remember('cities', self::TTL, fn () =>
            City::orderBy('name')->get(['id', 'name'])->toArray()
        );

        return response()->json(['data' => $cities]);
    }
}
