<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Motorcycle extends Model
{
    use HasFactory;    

    protected $fillable = [
        'brand_id',
        'category_id',
        'city_id',
        'model',
        'year',
        'price',
        'engine_cc',
        'mileage',
        'description',
        'status',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MotorcycleImage::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    public function scopeFilter(Builder $query, array $filters): void 
    {
        $query
            ->when($filters['brand_id'] ?? null,
                fn (Builder $q, $value) => $q->where('brand_id', $value))
            ->when($filters['category_id'] ?? null,
                fn (Builder $q, $value) => $q->where('category_id', $value))
            ->when($filters['city_id'] ?? null,
                fn (Builder $q, $value) => $q->where('city_id', $value))
            ->when($filters['price_min'] ?? null,
                fn (Builder $q, $value) => $q->where('price', '>=', $value))
            ->when($filters['price_max'] ?? null,
                fn (Builder $q, $value) => $q->where('price', '<=', $value))
            ->when($filters['engine_cc_min'] ?? null,
                fn (Builder $q, $value) => $q->where('engine_cc', '>=', $value))
            ->when($filters['engine_cc_max'] ?? null,
                fn (Builder $q, $value) => $q->where('engine_cc', '<=', $value))
            ->when($filters['search'] ?? null,
                fn (Builder $q, $value) => $q->where('model', 'like', "%{$value}%"));
    }

    public function scopeSort(Builder $query, ?string $sort): void 
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };
    }
}
