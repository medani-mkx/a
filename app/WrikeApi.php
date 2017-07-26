<?php

namespace App;

use MichaelKaefer\OAuth2\Client\Provider\Wrike;

class WrikeApi
{
    const SCHEME = 'https://';
    const API_PATH = 'api/v3';

    private $host = 'www.wrike.com'; // PROVISORISCH getAccessToken()->getValues()['host']

    private $provider = null;
    private $accessToken = null;

    public function __construct()
    {
        $this->provider = $this->getProvider();
        $this->accessToken = $this->getAccessToken();
    }

    ################################## PUBLIC METHODS START ##################################
    public function convertId($v2Id, $itemType)
    {
        /** statt curl-Option -g (globbing) gleich selbst [ ersetzt mit %5B und ] mit %5D
         * curl -X GET -H "Authorization: bearer 9Rla32XkOrJ3Y8Zy9gviJqi96Z2pQhmhbiUtc8ea0YiHjgsF1ZU6nKuytVL1Cvuf-N-N"
         * 'https://www.wrike.com/api/v3/ids?ids=%5B162149427%5D&type=ApiV2Folder'
         */
        $path = 'ids';
        $query = 'ids=%5B' . $v2Id . '%5D&type=ApiV2' . $itemType;

        $response = $this->get($path, $query);

        return $response[0]['id'];
    }

    public function getProjectTasks($wrikeProjectId)
    {
        /** statt curl-Option -g (globbing) gleich selbst [ ersetzt mit %5B und ] mit %5D
         * curl -X GET -H "Authorization: bearer 9Rla32XkOrJ3Y8Zy9gviJqi96Z2pQhmhbiUtc8ea0YiHjgsF1ZU6nKuytVL1Cvuf-N-N"
         * 'https://www.wrike.com/api/v3/folders/IEABIK4UI4E2UNBT/tasks?fields=%5B%22sharedIds%22,%22dependencyIds%22,%22briefDescription%22,%22parentIds%22,%22superParentIds%22,%22subTaskIds%22,%22responsibleIds%22,%22description%22,%22recurrent%22,%22authorIds%22,%22attachmentCount%22,%22hasAttachments%22,%22customFields%22,%22superTaskIds%22,%22metadata%22%5D&subTasks=true'
         * %5D&subTasks=true
         */
        $path = 'folders/' . $wrikeProjectId . '/tasks';
        $fields = '%22sharedIds%22,%22dependencyIds%22,%22briefDescription%22,%22parentIds%22,%22superParentIds%22,%22subTaskIds%22,%22responsibleIds%22,%22description%22,%22recurrent%22,%22authorIds%22,%22attachmentCount%22,%22hasAttachments%22,%22customFields%22,%22superTaskIds%22,%22metadata%22';
        $query = 'fields=%5B' . $fields . '%5D&subTasks=true';

        $response = $this->get($path, $query);

        return $response;
    }
    ################################## PUBLIC METHODS END ##################################

    private function getProvider()
    {
        if($this->provider === null) {
            $this->provider = new Wrike([
                'clientId'      => config('services.wrike.clientId'),
                'clientSecret'  => config('services.wrike.clientSecret'),
                'redirectUri'   => config('services.wrike.redirectUri')
            ]);
        }
        return $this->provider;
    }

    private function getAccessToken()
    {
        if($this->accessToken === null) {
            $this->accessToken = request()->session()->all()['wrike_access_token'];
        }
        if($this->accessToken->hasExpired()) {
            $this->accessToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $this->accessToken->getRefreshToken()
            ]);

            session([
                'wrike_access_token' => $this->accessToken,
            ]);
        }
        return $this->accessToken;
    }

    private function getRequest($method, $url)
    {
        $request = $this->provider->getAuthenticatedRequest(
            $method,
            $url,
            $this->getAccessToken()
        );
        return $request;
    }

    private function get($path, $query)
    {
        $url = $this->buildUrl($path, $query);

        $request = $this->getRequest('GET', $url);

        try {
            $response = $this->provider->getParsedResponse($request);
            $response = $response['data'];
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            dd($e->getMessage() . PHP_EOL . PHP_EOL . 'URL: ' . $url . PHP_EOL . PHP_EOL . '\League\OAuth2\Client\Provider\Exception\IdentityProviderException - ' . PHP_EOL . PHP_EOL . 'Suche im Sourcecode nach: "MKX-Fehler-1"');
        }

        return $response;
    }

    private function buildUrl($path, $query = '')
    {
        return self::SCHEME . $this->host . '/' . self::API_PATH . '/' . $path . ( !empty($query) ? '?' . $query : '' );
    }
}

