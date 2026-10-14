<?php

namespace App\Policies;

use App\Models\Motorcycle;
use App\Models\User;

class MotorcyclePolicy
{
    public function update(User $user, Motorcycle $motorcycle): bool
    {
        return $user->id === $motorcycle->user_id;
    }

    public function delete(User $user, Motorcycle $motorcycle): bool
    {
        return $user->id === $motorcycle->user_id;
    }
}
