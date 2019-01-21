<?php

namespace App\Policies;

use App\User;
use App\Word;
use Illuminate\Auth\Access\HandlesAuthorization;

class WordPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Word $word)
    {
        return true;
    }

    public function create(?User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Word $word)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Word $word)
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Word $word)
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Word $word)
    {
        return $user->isAdmin();
    }
}
