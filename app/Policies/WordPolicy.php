<?php

namespace App\Policies;

use App\User;
use App\Word;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WordPolicy
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

    public function view(?User $user, Word $word): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        return Response::deny("Vous devez être un admin pour ajouter un mot au dictionnaire !");
    }

    public function update(User $user): Response
    {
        return Response::deny("Vous devez être un admin pour modifier un mot du dictionnaire !");
    }

    public function delete(User $user, Word $word): Response
    {
        return Response::deny("Vous devez être un admin pour supprimer un mot du dictionnaire !");
    }

    public function restore(User $user, Word $word): Response
    {
        return Response::deny("Vous devez être un admin pour récupérer un mot du dictionnaire !");
    }

    public function forceDelete(User $user, Word $word): Response
    {
        return Response::deny("Vous devez être un admin pour supprimer un mot du dictionnaire !");
    }
}
