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

//                session([
//                    'mwd-wrike' => [
//                        "access_token"  => $accessToken->getToken(),
//                        "refresh_token" => $accessToken->getRefreshToken(),
//                        "expires_in"    => $accessToken->getExpires(),
//                        "token_type"    => $accessToken->getValues()['token_type'],
//                        "host"          => $accessToken->getValues()['host'],
//                    ],
//                ]);

                $resourceOwner = $wrike->getResourceOwner($accessToken);
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                exit($e->getMessage());
            }
        }




        
//        
//        dd($wrike);
//        // Bsp.: http://127.0.0.1:8000/?error=access_denied&error_description=Request%20denied%20by%20user
//        if($request->input('error') == 'access_denied' && $request->input('error_description') == 'Request denied by user') {
//            return 'Abbruch: Login über Wrike vom User abgelehnt. <a href="' . $this->url . '">Login</a>';
//        }
//        
//        // Bsp.: http://127.0.0.1:8000/?code=mnzgdIhdePFDqCkDBSwh8nztcmWY8Qet0h130Az36km4MwMOQyRWb985jRHvrbIT-N
//        else if(null !== $request->input('code')) {
//            
//            $postData = 'client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret . '&grant_type=authorization_code&code='.$request->input('code');            
//            $curloptUrl = 'https://www.wrike.com/oauth2/token';
//
//            // Bsp.: curl -X POST -d "client_id=y5IDCaV6&client_secret=yEB6pghtquRJE03nwZgCMCrFNMfbzHAeQXoYyV9EZK7xFjQSEJAwN142PC71KDHY&grant_type=authorization_code&code=3NyMA8KusMENDL3riePObiuLqpxSP9ChWih3UAi4TUuSMwm8a1A36xk7IZwKNxfZ-N" https://www.wrike.com/oauth2/token
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//            // curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
//            curl_setopt($ch, CURLOPT_URL, $curloptUrl);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $jsonResponse = curl_exec($ch);
//            curl_close ($ch);
//            
//            
//            $response = json_decode($jsonResponse);
//            
//            /*
//             *  Negative response example:
//             *  {
//             *      "error": "invalid_grant",
//             *      "error_description": "Authorization code is invalid",
//             *  }
//             */
//            if( isset($response->error) ) {
//                echo $response->error . ': ' . $response->error_description;
//                return Redirect::to($this->url);
//            }
//            
//            /*
//             *  Positive response example:
//             *  {
//             *      "access_token": "2YotnFZFEjr1zCsicMWpAA",
//             *      "refresh_token": "tGzv3JOkF0XG5Qx2TlKWIA",
//             *      "token_type": "bearer",
//             *      "expires_in": "3600",
//             *      "host": "www.wrike.com"
//             *  }
//             */
//            else {
//                
//                session([
//                    'mwd-wrike' => $response,
//                ]);
//                        
//                $curloptUrlRouteAndParams = 'contacts?me=true';
//                $curloptHttpHeaders = ['Authorization: ' . $response->token_type . ' ' . $response->access_token];
//                $curloptUrl = 'https://' . $response->host . '/api/v3/' . $curloptUrlRouteAndParams;
//                
//                // Bsp.: curl -X GET -H "Authorization: bearer O32tUQUnROMPGD7gySZVsTqRz4K8RCudAvbYofOjmtsHg9Y07vezLSDVVJKa06yV-N-N" https://www.wrike.com/api/v3/contacts?me=true
//                $ch = curl_init();
//                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, $curloptHttpHeaders);
////                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_setopt($ch, CURLOPT_URL, $curloptUrl);
////                curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
////                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//                $jsonResponse = curl_exec($ch);
//                curl_close($ch);
//                
//                $response =  json_decode($jsonResponse);
//                
//                
//                
//                echo '<pre>';
//                var_export($resourceOwner->toArray());
//                var_dump($resourceOwner->toArray());
//                dd('iiiiiiiii');
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
                
//            }
//            return 'ERROR ?';
//        }
//        
//        return Redirect::to($this->url);
    }
    
    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }
}

/*
string(237) "
{
    "access_token":"Sqmff3dZE1sUnMZ7QPtrqczwLFDqP5xOklthotORbUYCgVxavyKPArNyL4npFjpf-N-N",
    "token_type":"bearer",
    "host":"www.wrike.com",
    "expires_in":3600,
    "refresh_token":"ubGqEXWhED3gd5K3Zz7KQzEcUUvGTLpIE8XF5HONNfMqRsMV2JxoBpAIz58V1Npd-A-N"
}" 
 * */