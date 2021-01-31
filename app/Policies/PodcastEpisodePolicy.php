<?php

namespace App\Policies;

use App\User;
use App\PodcastEpisode;

use Illuminate\Auth\Access\HandlesAuthorization;

class PodcastEpisodePolicy
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

    public function view(?User $user, PodcastEpisode $podcastEpisode)
    {
        return true;
    }

    public function create(User $user)
    {
        return $this->deny("Vous devez être un admin pour ajouter un épisode de podcast !");
    }

    public function update(User $user, PodcastEpisode $podcastEpisode)
    {
        return $this->deny("Vous devez être un admin pour modifier un épisode de podcast !");
    }

    public function delete(User $user, PodcastEpisode $podcastEpisode)
    {
        return $this->deny("Vous devez être un admin pour supprimer un épisode de podcast !");
    }

    public function restore(User $user, PodcastEpisode $podcastEpisode)
    {
        return $this->deny("Vous devez être un admin pour récupérer un épisode de podcast !");
    }

    public function forceDelete(User $user, PodcastEpisode $podcastEpisode)
    {
        return $this->deny("Vous devez être un admin pour supprimer un épisode de podcast !");
    }
}
