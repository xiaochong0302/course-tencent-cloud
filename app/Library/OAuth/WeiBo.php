<?php

namespace App\Library\OAuth;

class WeiBo extends \App\Library\OAuth
{

    const AUTHORIZE_URL = 'https://api.weibo.com/oauth2/authorize';
    const ACCESS_TOKEN_URL = 'https://api.weibo.com/oauth2/oauth2/access_token';
    const USER_INFO_URL = 'https://api.weibo.com/2/users/show.json';

    public function getAuthorizeUrl()
    {
        $params = [
            'client_id' => $this->appId,
            'redirect_uri' => $this->appCallback,
            'response_type' => 'code',
        ];
        
        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    public function getAccessToken($code)
    {
        $params = [
            'code' => $code,
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri' => $this->appCallback,
            'grant_type' => 'authorization_code',
        ];
        
        $response = $this->httpPost(self::ACCESS_TOKEN_URL, $params);
        
        $this->accessToken = $this->parseAccessToken($response);
        
        return $this->accessToken;
    }

    public function getOpenId($accessToken = null)
    {
        return $this->openId;
    }

    public function getUserInfo($accessToken, $openId)
    {
        $params = [
            'access_token' => $accessToken,
            'uid' => $openId,
        ];
        
        $response = $this->httpGet(self::USER_INFO_URL, $params);
        
        return $this->parseUserInfo($response);
    }

    private function parseAccessToken($response)
    {
        $data = json_decode($response, true);
        
        if (!isset($data['access_token']) || isset($data['uid'])) {
            throw new \Exception("Fetch Access Token Failed:{$response}");
        }
        
        $this->openId = $data['uid'];
        
        return $data['access_token'];
    }

    private function parseUserInfo($response)
    {
        $data = json_decode($response, true);
        
        if ($data['error_code'] != 0) {
            throw new \Exception("Fetch User Info Failed:{$data['error']}");
        }
        
        $userInfo['type'] = 'WEIBO';
        $userInfo['name'] = $data['name'];
        $userInfo['nick'] = $data['screen_name'];
        $userInfo['head'] = $data['avatar_large'];
        
        return $userInfo;
    }

}
