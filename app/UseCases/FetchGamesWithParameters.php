<?php

namespace App\UseCases;

use App\Former\Game;
use App\Former\Session;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class FetchGamesWithParameters
{
    /**
     * @param Request $request
     * @return Builder
     */
    public static function perform(Request $request): Builder
    {
        $games = Game::with([
            'awards',
            'session',
            'screenshots',
            'contributors.member',
        ])->withCount([
            'nominations as awarded_categories_count' => fn($nominationsQuery) => $nominationsQuery->where('is_vainqueur', '>', '0')
        ])->withCount([
            'nominations as nominated_categories_count' => fn($nominationsQuery) => $nominationsQuery->where('is_vainqueur', '0')
        ]);

        if ($request->session_id) {
            if (!Session::sessionIdExists($request->session_id)) {
                abort(400, "This session does not exist");
            }

            $games = $games->where('id_session', $request->session_id);
        }

        if ($request->software) {
            $games = $games->where('support', $request->software);
        }

        if ($request->download_links) {
            if ($request->download_links == 'windows') {
                $games = $games->withWindowsDownloadLink();
            }
            if ($request->download_links == 'mac') {
                $games = $games->withMacDownloadLink();
            }
            if ($request->download_links == 'any') {
                $games = $games->where(fn($query) => $query->withWindowsDownloadLink()
                    ->orWhere
                    ->withMacDownloadLink()
                );
            }
            if ($request->download_links == 'none') {
                $games = $games->withoutDownloadLinks();
            }
        }

        if ($request->q) {
            $searchedTerm = "%" . $request->q . "%";

            $games = $games->where(fn($query) => $query->where('nom_jeu', 'like', $searchedTerm)
                ->orWhere('description_jeu', 'like', $searchedTerm)
                ->orWhere('genre_jeu', 'like', $searchedTerm)
                ->orWhere('theme', 'like', $searchedTerm)
                ->orWhere('support', 'like', $searchedTerm)
                ->orWhere('groupe', 'like', $searchedTerm)
            );
        }

        if ($request->sort) {
            $games = self::sortGamesFromQuery($games, $request->sort);
        }

        return $games;
    }

    /**
     * @param Builder $games
     * @param string $sort
     * @return Builder
     */
    private static function sortGamesFromQuery(Builder $games, string $sort): Builder
    {
        $columnNamesConverter = array_flip([
            'id_jeu' => 'id',
            'statut_jeu' => 'status',
            'nom_jeu' => 'title',
            'id_session' => 'session',
            'genre_jeu' => 'genre',
            'support' => 'software',
            'duree' => 'duration',
            'poids' => 'size',
            'site_officiel' => 'website',
            'groupe' => 'creation_group',
            'date_inscription' => 'created_at',
            'awards_count' => 'awards_count'
        ]);

        $DEFAULT_SORT_COLUMN = 'created_at';
        $DEFAULT_SORT_DIRECTION = 'asc';

        $sortingSegments = explode(',', $sort);
        foreach ($sortingSegments as $sortingSegment) {
            if (empty($sortingSegment)) {
                continue;
            }

            list($columnToSort, $sortingDirection) = array_pad(explode(":", $sortingSegment), 2, null);

            if (!$columnToSort) {
                $columnToSort = $DEFAULT_SORT_COLUMN;
            }

            if (!array_key_exists($columnToSort, $columnNamesConverter)) {
                continue;
            }

            if (!$sortingDirection) {
                $sortingDirection = $DEFAULT_SORT_DIRECTION;
            }

            if (array_key_exists($columnToSort, $columnNamesConverter)) {
                $columnToSort = $columnNamesConverter[$columnToSort];
            }

            if ($columnToSort == 'awards_count') {
                $games = $games->orderBy('awarded_categories_count', $sortingDirection)
                    ->orderBy('nominated_categories_count', $sortingDirection);
            } else {
                $games = $games->orderBy($columnToSort, $sortingDirection);
            }
        }

        return $games;
    }
}
