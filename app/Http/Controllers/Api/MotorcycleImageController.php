<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Motorcycle\StoreMotorcycleImageRequest;
use App\Http\Resources\MotorcycleImageResource;
use App\Models\Motorcycle;
use App\Models\MotorcycleImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MotorcycleImageController extends Controller
{
    public function store(StoreMotorcycleImageRequest $request, Motorcycle $motorcycle): JsonResponse
    {
        Gate::authorize('update', $motorcycle);

        $images = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store("motorcycles/{$motorcycle->id}", 'public');

            $images[] = $motorcycle->images()->create(['path' => $path]);
        }

        return MotorcycleImageResource::collection($images)
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(MotorcycleImage $image): Response
    {
        Gate::authorize('update', $image->motorcycle);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->noContent();
    }
}
