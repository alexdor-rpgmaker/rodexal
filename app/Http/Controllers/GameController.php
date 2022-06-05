<?php

namespace App\Http\Controllers;

use App\Pagination;
use App\Former\Game;
use App\Former\Member;
use App\Former\Session;

use App\Notifications\GameRegistered;
use App\Http\Requests\StoreGameRequest;
use App\UseCases\CleanGameAttributes;
use App\UseCases\FetchGamesWithParameters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Game::class, 'game');
    }

    public function index(Request $request)
    {
        $sessions = Session::orderBy('id_session')->get();

        $selectedSession = null;
        if ($request->query('session_id')) {
            $selectedSession = $sessions->firstWhere('id_session', $request->query('session_id'));
        }
        $currentSession = $sessions->last();

        $games = FetchGamesWithParameters::perform($request);

        $games = $games->orderByDesc('awarded_categories_count')
            ->orderByDesc('nominated_categories_count')
            ->orderBy('id_jeu')
            ->paginate(Pagination::perPage($request->per_page));

        $games->getCollection()->transform(fn($game) => CleanGameAttributes::perform($game, true));

        // TODO : Remove software category with only one game (move the game in "other" category)
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
        $currentSession = Session::orderByDesc('id_session')->first();

        return view('games.vue', [
            'selectedSession' => $session,
            'currentSession' => $currentSession
        ]);
    }

    public function create()
    {
        $currentSession = Session::orderByDesc('id_session')->first();

        // TODO: Abort if $currentSession->tooLateForGamesRegistration()

        return view('games.form', [
            'game' => new Game,
            'title' => 'Proposez un jeu',
            'subtitle' => 'Devenez un des challengers du concours des Alex d\'or',
            'form_method' => 'POST',
            'form_url' => route('jeux.store'),
            'currentSession' => $currentSession
        ]);
    }

    public function store(StoreGameRequest $request)
    {
        $member = Member::find(Auth::id());
        $currentSession = Session::orderByDesc('id_session')->first();

        abort_unless(
            $currentSession->allowsGamesRegistration(),
            Response::HTTP_FORBIDDEN,
            "Il n'est pas possible d'inscrire un jeu pour la session courante."
        );

        $game = Game::create([
            'id_session' => $currentSession->id_session,
            'nom_jeu' => $request->title,
            'support' => $request->software,
            'description_jeu' => $request->description,
            'avancement_jeu' => $request->progression == 'full' ? 1 : 0
        ]);
        $game->contributors()->create([
            'date_participant' => Carbon::now(),
            'id_membre' => $member->id_membre,
            // TODO : Don't fill those 2 columns when id_membre is filled
            'nom_membre' => $member->pseudo,
            'mail_membre' => $member->mail
        ]);

        $game->notify(new GameRegistered($game, Auth::user()));

        return redirect('/jeux')->with('status', 'Jeu bien inscrit !');
    }
}
