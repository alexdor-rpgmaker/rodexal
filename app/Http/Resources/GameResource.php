<?php

namespace App\Http\Resources;

use App\Former\Session;
use App\Helpers\StringParser;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $game = $this->cleanAttributes($this, [
            'nom_jeu', 'taille', 'genre_jeu', 'theme', 'duree', 'support', 'site_officiel', 'groupe'
        ]);

        return [
            'id' => $game->id_jeu,
            'status' => $game->getStatus(),
            'title' => $game->nom_jeu,
            'session' => [
                'id' => $game->session->id_session,
                'name' => $game->session->nom_session
            ],
            'genre' => $game->genre_jeu,
            'software' => $game->support,
            'theme' => $game->theme,
            'duration' => $game->duree,
            'size' => $game->poids,
            'website' => $game->site_officiel,
            'creation_group' => $game->groupe,
            'logo' => $game->getLogoUrl(),
            'created_at' => $this->formatDateOrNullify($game->date_inscription),
            'description' => $game->description_jeu,
            'download_links' => $this->extractDownloadLinks($game),
            'awards' => GameAwardResource::collection($this->whenLoaded('awards')),
            'authors' => ContributorResource::collection($this->whenLoaded('contributors')),
            // TODO: Use ScreenshotResource when screenshot URL does not depend on session any more
            'screenshots' => $game->screenshots->transform($this->parseScreenshots($game))
        ];
    }

    private function extractDownloadLinks($game)
    {
        $downloadLinks = [];
        if (!empty($game->lien_sur_site) || !empty($game->lien)) {
            $downloadLinks[] = [
                'platform' => 'windows',
                'url' => !empty($game->lien_sur_site) ? $this->onWebsiteDownloadUrl($game, 'lien_sur_site') : $game->lien,
            ];
        }
        if (!empty($game->lien_sur_site_sur_mac) || !empty($game->lien_sur_mac)) {
            $downloadLinks[] = [
                'platform' => 'mac',
                'url' => !empty($game->lien_sur_site_sur_mac) ? $this->onWebsiteDownloadUrl($game, 'lien_sur_site_sur_mac') : $game->lien_sur_mac,
            ];
        }

        return $downloadLinks;
    }

    private function onWebsiteDownloadUrl($game, $downloadColumn)
    {
        $downloadUrl = env('FORMER_APP_URL') . '/archives/';
        $downloadUrl .= Session::nameFromId($game->session->id_session);
        $downloadUrl .= '/jeux/' . StringParser::html($game->{$downloadColumn});
        return $downloadUrl;
    }

    /**
     * @param $game
     * @return Closure
     */
    private function parseScreenshots($game): Closure
    {
        return function ($screenshot) use ($game) {
            return [
                'title' => StringParser::parseOrNullify($screenshot->nom_screenshot),
                'url' => $screenshot->getImageUrlForSession($game->session->id_session),
            ];
        };
    }

    private function formatDateOrNullify($date)
    {
        return empty($date) ? null : $date->format('c');
    }

    private function cleanAttributes($object, $attributes)
    {
        foreach ($attributes as $attribute) {
            $object->{$attribute} = StringParser::parseOrNullify($object->{$attribute});
        }
        return $object;
    }
}
