<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleImage extends Model
{
    protected $fillable = ['path'];

    public function motorcycle(): Belongsto
    {
        return $this->belongsTo(Motorcycle::class);
    }
}
