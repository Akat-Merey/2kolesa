<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Motorcycle\StoreMotorcycleRequest;
use App\Http\Requests\Motorcycle\UpdateMotorcycleRequest;
use App\Http\Resources\MotorcycleResource;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MotorcycleController extends Controller
{
    private const RELATIONS = ['brand', 'category', 'city', 'user', 'images'];

    public function index(): AnonymousResourceCollection
    {
        $motorcycles = Motorcycle::with(self::RELATIONS)
            ->where('status', 'active')
            ->latest()
            ->paginate(15);

        return MotorcycleResource::collection($motorcycles);
    }

    public function show(Motorcycle $motorcycle): MotorcycleResource
    {
        return new MotorcycleResource($motorcycle->load(self::RELATIONS));
    }

    public function store(StoreMotorcycleRequest $request): MotorcycleResource
    {
        $motorcycle = $request->user()->motorcycles()->create($request->validated());

        return new MotorcycleResource($motorcycle->load(self::RELATIONS));
    }   

    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle): MotorcycleResource
    {
        Gate::authorize('update', $motorcycle);

        $motorcycle->update($request->validated());

        return new MotorcycleResource($motorcycle->load(self::RELATIONS));
    }

    public function destroy(Motorcycle $motorcycle): Response
    {
        Gate::authorize('delete', $motorcycle);

        Storage::disk('public')->deleteDirectory("motorcycles/{$motorcycle->id}");
        $motorcycle->delete();

        return response()->noContent();
    }

    public function mine(Request $request): AnonymousResourceCollection
    {
        $motorcycles = $request->user()
            ->motorcycles()
            ->with(self::RELATIONS)
            ->latest()
            ->paginate(15);
        
        return MotorcycleResource::collection($motorcycles);
    }
}
