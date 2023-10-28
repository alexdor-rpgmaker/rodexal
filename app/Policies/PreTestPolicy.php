<?php

namespace App\Policies;

use App\PreTest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PreTestPolicy
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

    public function view(?User $user, PreTest $preTest): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        if ($user->isJury()) {
            return Response::allow();
        }
        return Response::deny("Vous devez être un juré pour créer une pré-qualif/un QCM !");
    }

    public function update(User $user, PreTest $preTest): Response
    {
        if (!$user->isJury()) {
            return Response::deny("Vous devez être un juré pour modifier une pré-qualif/un QCM !");
        }
        if ($preTest->user_id != $user->id) {
            return Response::deny("Vous devez être l'auteur de la pré-qualif/du QCM pour pouvoir la/le modifier !");
        }
        return Response::allow();
    }

    public function delete(User $user, PreTest $preTest): Response
    {
        // return Response::denyWithStatus(404);
        // return Response::denyAsNotFound();
        return Response::deny("Vous devez être un admin pour supprimer une pré-qualif/un QCM !");
    }

    public function restore(User $user, PreTest $preTest): Response
    {
        return Response::deny("Vous devez être un admin pour récupérer une pré-qualif/un QCM !");
    }

    public function forceDelete(User $user, PreTest $preTest): Response
    {
        return Response::deny("Vous devez être un admin pour supprimer une pré-qualif/un QCM !");
    }
}
