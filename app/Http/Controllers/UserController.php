<?php

namespace App\Http\Controllers;

use App\User;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use League\OAuth2\Client\Provider\GenericProvider as GenericOAuth2Provider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use UnexpectedValueException;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    public function callback(Request $request)
    {
        $provider = new GenericOAuth2Provider([
            'clientId' => env('FORMER_APP_CLIENT_ID'),
            'clientSecret' => env('FORMER_APP_CLIENT_SECRET'),
            'redirectUri' => env('APP_URL') . env('APP_OAUTH_URL'),
            'urlAuthorize' => env('FORMER_APP_URL') . env('FORMER_APP_AUTHORIZATION_URL_SUFFIX'),
            'urlAccessToken' => env('FORMER_APP_URL') . env('FORMER_APP_TOKEN_URL_SUFFIX'),
            'urlResourceOwnerDetails' => env('FORMER_APP_URL') . env('FORMER_APP_RESOURCE_OWNER_URL_SUFFIX')
        ]);

        $guzzleClient = new GuzzleClient([
            'defaults' => [
                RequestOptions::CONNECT_TIMEOUT => 5,
                RequestOptions::ALLOW_REDIRECTS => true
            ],
            RequestOptions::VERIFY => App::environment('local') !== true,
        ]);

        $provider->setHttpClient($guzzleClient);

        if (!isset($request->code)) {
            session(['oauth2state' => $provider->getState()]);
            return redirect($provider->getAuthorizationUrl());
        } elseif (empty($request->state) || (session('oauth2state') !== null && $request->state !== session('oauth2state'))) {
            if (session('oauth2state') !== null) {
                $request->session()->forget('oauth2state');
            }
            exit('Invalid state');
        } else {
            try {
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $request->code
                ]);

                $resourceOwner = $provider->getResourceOwner($accessToken);
                $resourceOwnerArray = $resourceOwner->toArray();

                session(['resource-owner' => $resourceOwnerArray]);

                $user = User::where('id', $resourceOwnerArray['id'])->first();
                if (!$user) {
                    $user = new User([
                        'id' => $resourceOwnerArray['id'],
                        'password' => bcrypt(Str::random(10))
                    ]);
                }
                $user->fill([
                    'name' => $resourceOwnerArray['username'],
                    'email' => $resourceOwnerArray['email'],
                    'rank' => $resourceOwnerArray['rank']
                ]);
                $user->save();

                $remember = true;
                Auth::login($user, $remember);

                if ($request->session()->has('urlBeforeLogin')) {
                    $urlBeforeLogin = $request->session()->pull('urlBeforeLogin');
                    Log::info('Redirect user to URL before login', [
                        'user' => $user->name,
                        'urlBeforeLogin' => $urlBeforeLogin
                    ]);
                    return redirect($urlBeforeLogin);
                } else {
                    return redirect('/dictionnaire');
                }
            } catch (IdentityProviderException | UnexpectedValueException $e) {
                Log::emergency($e);
                return redirect('/dictionnaire')->with('status', 'Erreur dans la connexion...');
            }
        }
    }
}
