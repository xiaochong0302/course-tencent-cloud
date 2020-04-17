<?php

namespace App\Library\OAuth;

use App\Library\OAuth;

class WeiXin extends OAuth
{

    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/qrconnect';
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    const USER_INFO_URL = 'https://api.weixin.qq.com/sns/userinfo';

    public function getAuthorizeUrl()
    {
        $params = [
            'appid' => $this->appId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'snsapi_login',
            'state' => 'dev',
        ];
        
        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    public function getAccessToken($code)
    {
        $params = [
            'code' => $code,
            'appid' => $this->appId,
            'secret' => $this->appSecret,
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
            'openid' => $openId,
        ];
        
        $response = $this->httpGet(self::USER_INFO_URL, $params);
        
        return $this->parseUserInfo($response);
    }

    private function parseAccessToken($response)
    {
        $data = json_decode($response, true);
        
        if (isset($data['errcode']) && $data['errcode'] != 0) {
            throw new \Exception("Fetch Access Token Failed:{$data['errmsg']}");
        }
        
        $this->openId = $data['openid'];
        
        return $data['access_token'];
    }

    private function parseUserInfo($response)
    {
        $data = json_decode($response, true);
        
        if (isset($data['errcode']) && $data['errcode'] != 0) {
            throw new \Exception("Fetch User Info Failed:{$data['errmsg']}");
        }
        
        $userInfo['type'] = 'WEIXIN';
        $userInfo['name'] = $data['name'];
        $userInfo['nick'] = $data['screen_name'];
        $userInfo['head'] = $data['avatar_large'];
        
        return $userInfo;
    }

}
