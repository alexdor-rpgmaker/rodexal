<?php

namespace App\Http\Controllers\Api\V0;

use App\Former\JukeboxMusic;
use App\Http\Controllers\Controller;
use App\Http\Resources\JukeboxMusicResource;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JukeboxMusicApiController extends Controller
{
    public function index(Request $request)
    {
        $jukeboxMusics = JukeboxMusic::with(['game'])
            ->where('statut_zik', '=', 1)
            ->orderBy('titre')
            ->get();

        return JukeboxMusicResource::collection($jukeboxMusics)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
