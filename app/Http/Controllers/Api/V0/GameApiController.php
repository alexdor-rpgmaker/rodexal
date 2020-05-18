<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\UseCases\FetchGamesWithParameters;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        $games = FetchGamesWithParameters::perform($request);

        $PER_PAGE_DEFAULT = 50;
        $perPage = isset($request->per_page) && $this->between1And50($request->per_page) ? (int)$request->per_page : $PER_PAGE_DEFAULT;

        $games = $games->orderBy('id_session')
            ->orderBy('id_jeu')
            ->paginate($perPage);

        $statusCode = $games->hasPages() ? Response::HTTP_PARTIAL_CONTENT : Response::HTTP_OK;
        return GameResource::collection($games)
            ->response()
            ->setStatusCode($statusCode);
    }

    private function between1And50($number)
    {
        return in_array(intval($number), range(1, 50));
    }
}
