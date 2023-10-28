<?php

namespace App\Policies;

use App\Former\Game;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Game $game): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return true;
    }
}
