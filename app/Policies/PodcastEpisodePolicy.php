<?php

namespace App\Policies;

use App\PodcastEpisode;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PodcastEpisodePolicy
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

    public function view(?User $user, PodcastEpisode $podcastEpisode): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        return Response::deny("Vous devez être un admin pour ajouter un épisode de podcast !");
    }

    public function update(User $user, PodcastEpisode $podcastEpisode): Response
    {
        return Response::deny("Vous devez être un admin pour modifier un épisode de podcast !");
    }

    public function delete(User $user, PodcastEpisode $podcastEpisode): Response
    {
        return Response::deny("Vous devez être un admin pour supprimer un épisode de podcast !");
    }

    public function restore(User $user, PodcastEpisode $podcastEpisode): Response
    {
        return Response::deny("Vous devez être un admin pour récupérer un épisode de podcast !");
    }

    public function forceDelete(User $user, PodcastEpisode $podcastEpisode): Response
    {
        return Response::deny("Vous devez être un admin pour supprimer un épisode de podcast !");
    }
}
