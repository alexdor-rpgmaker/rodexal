<?php

namespace App\Http\Controllers;

use App\Former\Game;
use App\Former\Session;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $sessions = Session::orderBy('id_session')->get();

        $selectedSession = null;
        if ($request->query('session_id')) {
            $selectedSession = $sessions->firstWhere('id_session', $request->query('session_id'));
        }
        $currentSession = $sessions->last();

        $games = Game::with([
            'awards',
            'session',
            'screenshots',
            'contributors.member',
        ])->withCount(['nominations as awarded_categories_count' => function ($nominationsQuery) {
            $nominationsQuery->where('is_vainqueur', '>', '0');
        }])->withCount(['nominations as nominated_categories_count' => function ($nominationsQuery) {
            $nominationsQuery->where('is_vainqueur', '0');
        }]);

        if ($request->session_id) {
            if (!Session::sessionIdExists($request->session_id)) {
                abort(400, "This session does not exist");
            }

            $games = $games->where('id_session', $request->session_id);
        }

        if ($request->software) {
            $games = $games->where('support', $request->software);
        }

        if ($request->download_links == "any") {
            if ($request->download_links == 'windows') {
                $games = $games->withWindowsDownloadLink();
            }
            if ($request->download_links == 'mac') {
                $games = $games->withMacDownloadLink();
            }
            if ($request->download_links == 'any') {
                $games = $games->where(function ($query) {
                    $query->withWindowsDownloadLink()
                        ->orWhere
                        ->withMacDownloadLink();
                });
            }
            if ($request->download_links == 'none') {
                $games = $games->withoutDownloadLinks();
            }
        }

        if ($request->q) {
            $searchedTerm = "%" . $request->q . "%";

            $games = $games->where(function ($query) use ($searchedTerm) {
                $query->where('nom_jeu', 'like', $searchedTerm)
                    ->orWhere('description_jeu', 'like', $searchedTerm)
                    ->orWhere('genre_jeu', 'like', $searchedTerm)
                    ->orWhere('theme', 'like', $searchedTerm)
                    ->orWhere('support', 'like', $searchedTerm)
                    ->orWhere('groupe', 'like', $searchedTerm);
            });
        }

        if ($request->sort) {
            $games = $this->sortGamesFromQuery($games, $request->sort);
        }

        $PER_PAGE_DEFAULT = 50;
        $perPage = isset($request->per_page) && $this->between1And50($request->per_page) ? (int)$request->per_page : $PER_PAGE_DEFAULT;

        $games = $games->orderBy('awarded_categories_count', 'desc')
            ->orderBy('nominated_categories_count', 'desc')
            ->orderBy('id_jeu')
            ->paginate($perPage);

        // TODO : Group 1-game software in "other" category
        $softwares = Game::select("support")->distinct()->where('support', '!=', '')->orderBy('support')->pluck("support");

        $request->flash();
        return view('games.index', [
            'games' => $games,
            'sessions' => $sessions,
            'softwares' => $softwares,
            'currentSession' => $currentSession,
            'selectedSession' => $selectedSession
        ]);
    }

    public function vue(Request $request)
    {
        $session = null;
        if ($request->query('session_id')) {
            $session = Session::find($request->query('session_id'));
        }
        $currentSession = Session::orderBy('id_session', 'desc')->first();

        return view('games.vue', [
            'selectedSession' => $session,
            'currentSession' => $currentSession
        ]);
    }

    private function between1And50($number)
    {
        return in_array(intval($number), range(1, 50));
    }

    /**
     * @param Builder $games
     * @param string $sort
     * @return Builder
     */
    private function sortGamesFromQuery(Builder $games, string $sort): Builder
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
