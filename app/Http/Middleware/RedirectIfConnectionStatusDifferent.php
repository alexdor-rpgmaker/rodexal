<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfConnectionStatusDifferent
{
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->path() == "oauth/callback") {
            return $next($request);
        } elseif (!Auth::check()) {
            $client = new \GuzzleHttp\Client(['base_uri' => env('FORMER_APP_URL')]);
            $guzzleRequest = new \GuzzleHttp\Psr7\Request('GET', env('FORMER_APP_IS_CONNECTED_ENDPOINT'));
            $response = $client->send($guzzleRequest);
            $responseBody = json_decode($response->getBody());
            if($responseBody->connected)
            {
                return redirect(url('/oauth/callback'));
            }
        }

        return $next($request);
    }
}
