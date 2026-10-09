<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reserva $reserva): bool
    {
        return $user->is_admin || $user->id === $reserva->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Reserva $reserva): bool
    {
        return $user->is_admin || $user->id === $reserva->user_id;
    }

    public function delete(User $user, Reserva $reserva): bool
    {
        return $user->is_admin;
    }
}
