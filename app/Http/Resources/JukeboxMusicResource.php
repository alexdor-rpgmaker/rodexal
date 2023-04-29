<?php

namespace App\Http\Resources;

use App\Former\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JukeboxMusicResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->titre,
            'description' => $this->com,
            'music_url' => "https://www.alexdor.info/zik/mp3/" . $this->url_fichier . ".mp3",
            'game' => [
                'id' => $this->game->id_jeu,
                'title' => $this->game->nom_jeu,
                'session' => Session::nameFromId($this->game->id_session)
            ]
        ];
    }
}
