<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use League\OAuth2\Client\Provider\GenericProvider as GenericOAuth2Provider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    public function callback(Request $request)
    {
        $provider = new GenericOAuth2Provider([
            'clientId'                => env('FORMER_APP_CLIENT_ID'),
            'clientSecret'            => env('FORMER_APP_CLIENT_SECRET'),
            'redirectUri'             => env('APP_URL').env('APP_OAUTH_URL'),
            'urlAuthorize'            => env('FORMER_APP_URL').env('FORMER_APP_AUTHORIZATION_URL_SUFFIX'),
            'urlAccessToken'          => env('FORMER_APP_URL').env('FORMER_APP_TOKEN_URL_SUFFIX'),
            'urlResourceOwnerDetails' => env('FORMER_APP_URL').env('FORMER_APP_RESOURCE_OWNER_URL_SUFFIX')
        ]);

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

                $user = User::where('id', $resourceOwnerArray['id'])->first();
                if ($user === null) {
                    $user = new User;
                    $user->id = $resourceOwnerArray['id'];
                    $user->name = $resourceOwnerArray['username'];
                    $user->email = $resourceOwnerArray['email'];
                    $user->rank = $resourceOwnerArray['rank'];
                    $user->password = bcrypt(str_random(10));
                    $user->save();
                }

                Auth::login($user);

                return redirect('/dictionnaire')->with('status', 'Bien connecté !');

            } catch (IdentityProviderException $e) {
                exit($e->getMessage());
            }
        }
    }
}
