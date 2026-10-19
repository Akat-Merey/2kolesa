<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotorcycleResource;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FavoriteController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $favorites = $request->user()
            ->favorites()
            ->with(Motorcycle::RELATIONS)
            ->orderByPivot('created_at', 'desc')
            ->paginate(15);

            return MotorcycleResource::collection($favorites);
    }

    public function store(Request $request, Motorcycle $motorcycle): Response
    {
        $request->user()->favorites()->syncWithoutDetaching([$motorcycle->id]);

        return response()->noContent();
    }

    public function destroy(Request $request, Motorcycle $motorcycle): Response
    {
        $request->user()->favorites()->detach($motorcycle->id);

        return response()->noContent();
    }
}
