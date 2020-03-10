<?php

namespace App\Policies;

use App\User;
use App\Word;
use Illuminate\Auth\Access\HandlesAuthorization;

class WordPolicy
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

    public function view(?User $user, Word $word)
    {
        return true;
    }

    public function create(User $user)
    {
        return $this->deny("Vous devez être un admin pour ajouter un mot au dictionnaire !");
    }

    public function update(User $user)
    {
        return $this->deny("Vous devez être un admin pour modifier un mot du dictionnaire !");
    }

    public function delete(User $user, Word $word)
    {
        return $this->deny("Vous devez être un admin pour supprimer un mot du dictionnaire !");
    }

    public function restore(User $user, Word $word)
    {
        return $this->deny("Vous devez être un admin pour récupérer un mot du dictionnaire !");
    }

    public function forceDelete(User $user, Word $word)
    {
        return $this->deny("Vous devez être un admin pour supprimer un mot du dictionnaire !");
    }
}
