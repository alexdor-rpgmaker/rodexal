<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\V0;

use App\Pagination;
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

        $games = $games->orderBy('id_session')
            ->orderBy('id_jeu')
            ->paginate(Pagination::perPage($request->per_page));

        $statusCode = $games->hasPages() ? Response::HTTP_PARTIAL_CONTENT : Response::HTTP_OK;
        return GameResource::collection($games)
            ->response()
            ->setStatusCode($statusCode);
    }
}
