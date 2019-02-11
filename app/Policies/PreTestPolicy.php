<?php

namespace App\Policies;

use Log;
use App\User;
use App\PreTest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreTestPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(?User $user, PreTest $preTest)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isJury();
    }

    public function update(User $user, PreTest $preTest)
    {
        return $user->isJury() && $preTest->user_id == $user->id;
    }

    public function delete(User $user, PreTest $preTest)
    {
        return false;
    }

    public function restore(User $user, PreTest $preTest)
    {
        return false;
    }

    public function forceDelete(User $user, PreTest $preTest)
    {
        return false;
    }
}
