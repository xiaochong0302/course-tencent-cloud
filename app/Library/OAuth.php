<?php

namespace App\Library;

use GuzzleHttp\Client as HttpClient;

abstract class OAuth
{

    protected $appId;
    protected $appSecret;
    protected $appCallback;
    protected $accessToken;
    protected $openId;

    public function __construct($appId, $appSecret, $appCallback)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->appCallback = $appCallback;
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

    abstract public function getAuthorizeUrl();

    abstract public function getAccessToken($code);

    abstract public function getOpenId($accessToken);

    abstract public function getUserInfo($accessToken, $openId);

}
