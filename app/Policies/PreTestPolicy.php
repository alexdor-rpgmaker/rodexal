<?php

namespace App\Policies;

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

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, PreTest $preTest)
    {
        return true;
    }

    public function create(User $user)
    {
        if ($user->isJury()) {
            return true;
        }
        return $this->deny("Vous devez être un juré pour créer un QCM !");
    }

    public function update(User $user, PreTest $preTest)
    {
        if ($user->isJury() && $preTest->user_id == $user->id) {
            return true;
        }
        return $this->deny("Vous devez être un juré pour modifier un QCM !");
    }

    public function delete(User $user, PreTest $preTest)
    {
        return $this->deny("Vous devez être un admin pour supprimer un QCM !");
    }

    public function restore(User $user, PreTest $preTest)
    {
        return $this->deny("Vous devez être un admin pour restorer un QCM !");
    }

    public function forceDelete(User $user, PreTest $preTest)
    {
        return $this->deny("Vous devez être un admin pour supprimer un QCM !");
    }
}
