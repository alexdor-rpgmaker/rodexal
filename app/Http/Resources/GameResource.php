<?php

namespace App\Http\Resources;

use App\Helpers\StringParser;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * @param Request $request
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
            'description' => StringParser::apiRichText($game->description_jeu),
            'download_links' => $this->extractDownloadLinks($game),
            'awards' => GameAwardResource::collection($this->whenLoaded('awards')),
            'authors' => ContributorResource::collection($this->whenLoaded('contributors')),
            // TODO: Use ScreenshotResource when screenshot URL does not depend on session any more
            'screenshots' => $game->screenshots->transform($this->parseScreenshots($game))
        ];
    }

    private function extractDownloadLinks($game)
    {
        if ($game->link_removed_on_author_demand) {
            return [];
        }

        $downloadLinks = [];
        if ($game->hasWindowsDownloadLink()) {
            $downloadLinks[] = [
                'platform' => 'windows',
                'url' => $game->getWindowsDownloadLink(),
            ];
        }
        if ($game->hasMacDownloadLink()) {
            $downloadLinks[] = [
                'platform' => 'mac',
                'url' => $game->getMacDownloadLink(),
            ];
        }

        return $downloadLinks;
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

    /**
     * @param Carbon|null $date
     * @return string|null
     */
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
