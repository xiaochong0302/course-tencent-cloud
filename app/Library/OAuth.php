<?php

namespace App\Library;

use GuzzleHttp\Client as HttpClient;
use Phalcon\Crypt;
use Phalcon\Di;

abstract class OAuth
{

    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $accessToken;
    protected $openId;

    public function __construct($clientId, $clientSecret, $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    public function httpGet($uri, $params = [], $headers = [])
    {
        $client = new HttpClient();

        $options = ['query' => $params, 'headers' => $headers];

        $response = $client->get($uri, $options);

        return $response->getBody();
    }

    public function httpPost($uri, $params = [], $headers = [])
    {
        $client = new HttpClient();

        $options = ['query' => $params, 'headers' => $headers];

        $response = $client->post($uri, $options);

        return $response->getBody();
    }

    public function getState()
    {
        /**
         * @var $crypt Crypt
         */
        $crypt = Di::getDefault()->get('crypt');

        return $crypt->encryptBase64(rand(1000, 9999));
    }

    public function checkState($state)
    {
        /**
         * @var $crypt Crypt
         */
        $crypt = Di::getDefault()->get('crypt');

        $value = $crypt->decryptBase64($state);

        if ($value < 1000 || $value > 9999) {
            throw new \Exception('Invalid OAuth State Value');
        }

        return true;
    }

    abstract public function getAuthorizeUrl();

    abstract public function getAccessToken($code);

    abstract public function getOpenId($accessToken);

    abstract public function getUserInfo($accessToken, $openId);

}
