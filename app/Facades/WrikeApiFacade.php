<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WrikeApiFacade extends Facade {
    private const PROTOCOL = 'https://';
    private const API_PATH = '/api/v3/';
    private static $accessToken = null;
    private static $tokenType = null;
    private static $host = null;
    private static $expiresIn = null;
    private static $refreshToken = null;
    
    protected static function getFacadeAccessor()
    {
        return 'WrikeApiFacade';
    }
    
    private static function setSession()
    {
        if(is_null(self::$accessToken)){
            self::$accessToken = request()->session()->all()['mwd-wrike']->access_token;
            self::$tokenType = request()->session()->all()['mwd-wrike']->token_type;
            self::$host = request()->session()->all()['mwd-wrike']->host;
            self::$expiresIn = request()->session()->all()['mwd-wrike']->expires_in;
            self::$refreshToken = request()->session()->all()['mwd-wrike']->refresh_token;
        }
    }
    
    public static function convertLegacyId($legacyId, $itemType)
    {
        self::setSession();
        /** statt curl-Option -g (globbing) gleich selbst [ ersetzt mit %5B und ] mit %5D
         * curl -X GET -H "Authorization: bearer 9Rla32XkOrJ3Y8Zy9gviJqi96Z2pQhmhbiUtc8ea0YiHjgsF1ZU6nKuytVL1Cvuf-N-N" 
         * 'https://www.wrike.com/api/v3/ids?ids=%5B162149427%5D&type=ApiV2Folder'
         */
        $urlWithParams = 'ids?ids=%5B' . $legacyId . '%5D&type=ApiV2' . $itemType;
        $response = self::curlGet($urlWithParams);
        return $response->data[0]->id;
    }
    
    public static function getProjectTasks($wrikeProjectId)
    {
        self::setSession();
        /** statt curl-Option -g (globbing) gleich selbst [ ersetzt mit %5B und ] mit %5D
         * curl -X GET -H "Authorization: bearer 9Rla32XkOrJ3Y8Zy9gviJqi96Z2pQhmhbiUtc8ea0YiHjgsF1ZU6nKuytVL1Cvuf-N-N" 
         * 'https://www.wrike.com/api/v3/folders/IEABIK4UI4E2UNBT/tasks?fields=%5B%22sharedIds%22,%22dependencyIds%22,%22briefDescription%22,%22parentIds%22,%22superParentIds%22,%22subTaskIds%22,%22responsibleIds%22,%22description%22,%22recurrent%22,%22authorIds%22,%22attachmentCount%22,%22hasAttachments%22,%22customFields%22,%22superTaskIds%22,%22metadata%22%5D&subTasks=true'
         * %5D&subTasks=true
         */
        $fields = '%22sharedIds%22,%22dependencyIds%22,%22briefDescription%22,%22parentIds%22,%22superParentIds%22,%22subTaskIds%22,%22responsibleIds%22,%22description%22,%22recurrent%22,%22authorIds%22,%22attachmentCount%22,%22hasAttachments%22,%22customFields%22,%22superTaskIds%22,%22metadata%22';
        $params = '?fields=%5B' . $fields . '%5D&subTasks=true';
        $urlWithParams = 'folders/' . $wrikeProjectId . '/tasks' . $params;
        $response = self::curlGet($urlWithParams);
        return $response->data;
    }
    
    private static function curlGet($urlWithParams) {
        $url = self::buildUrl($urlWithParams);
        $headers = self::buildHeaders();

        /** curl -X GET -H "<headers>" <url> */
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        $jsonResponse = curl_exec($ch);
        
        curl_close($ch);

        return json_decode($jsonResponse);
    }
    
    private static function buildUrl($urlWithParams)
    {
        return self::PROTOCOL . self::$host . self::API_PATH . $urlWithParams;
    }
    
    private static function buildHeaders()
    {
        return ['Authorization: ' . self::$tokenType . ' ' . self::$accessToken];
    }
}

