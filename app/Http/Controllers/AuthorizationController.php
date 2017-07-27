<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use MichaelKaefer\OAuth2\Client\Provider\Wrike;

class AuthorizationController extends Controller
{
    private $clientId;
    private $url;
    private $clientSecret;


    public function __construct() {
        $this->clientId = 'y5IDCaV6';
        $this->clientSecret = 'yEB6pghtquRJE03nwZgCMCrFNMfbzHAeQXoYyV9EZK7xFjQSEJAwN142PC71KDHY';
        $this->url = 'https://www.wrike.com/oauth2/authorize?client_id=' . $this->clientId . '&response_type=code';
    }
    
    public function index(Request $request)
    {
        $wrike = new Wrike([
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'redirectUri'             => ''
        ]);
        
        // Get code from Wrike API
        if (null == $request->input('code')) {
            $authorizationUrl = $wrike->getAuthorizationUrl();
            session(['oauth2state' => $wrike->getState()]);
            header('Location: ' . $authorizationUrl);
            exit;
        // Check for errors
        } elseif (null == $request->input('state') || ($request->session()->has('oauth2state') && $request->input('state') !== $request->session()->get('oauth2state')) ) {
            if ($request->session()->has('oauth2state')) {
                $request->session()->pull('oauth2state');
            }
            exit('###MKX### Invalid state');
        // Get access token and resource owner from Wrike API
        } else {
            try {
                $accessToken = $wrike->getAccessToken('authorization_code', [
                    'code' => $request->input('code')
                ]);

                session([
                    'wrike_access_token' => $accessToken,
                ]);

                $resourceOwner = $wrike->getResourceOwner($accessToken);
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                exit($e->getMessage());
            }
        }

        $resourceOwnerArray = $resourceOwner->toArray();

        $wrikeUserId = $resourceOwner->getId();
        $password = Hash::make($wrikeUserId);
        $email = $resourceOwnerArray['data'][0]['profiles'][0]['email'];
        $firstName = $resourceOwnerArray['data'][0]['firstName'];
        $lastName = $resourceOwnerArray['data'][0]['lastName'];
        $avatarUrl = $resourceOwnerArray['data'][0]['avatarUrl'];


        if( $wrikeUserId ) {
            if( User::where('wrike_user_id', '=', $wrikeUserId)->exists()) {
                $user = User::where('wrike_user_id', '=', $wrikeUserId)->first();
            } else {
                $user = new User();
            }

            $user->wrike_user_id    = $wrikeUserId;
            $user->password         = $password;
            $user->email            = $email;
            $user->first_name       = $firstName;
            $user->last_name        = $lastName;
            $user->avatarUrl        = $avatarUrl;

            $user->save();

            /* Login user */
            if (Auth::attempt(['email' => $user->email, 'password' => $wrikeUserId])) {
                return redirect()->intended('offers');
            }
            else {
                return 'ERROR Login fehlgeschlagen.';
            }
        }
    }
    
    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }
}