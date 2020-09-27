<?php

namespace App\Library\OAuth;

use App\Library\OAuth;

class QQ extends OAuth
{

    const AUTHORIZE_URL = 'https://graph.qq.com/oauth2.0/authorize';
    const ACCESS_TOKEN_URL = 'https://graph.qq.com/oauth2.0/token';
    const OPENID_URL = 'https://graph.qq.com/oauth2.0/me';
    const USER_INFO_URL = 'https://graph.qq.com/user/get_user_info';

    public function getAuthorizeUrl()
    {
        $params = [
            'client_id' => $this->appId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => '',
        ];
        
        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    public function getAccessToken($code)
    {
        $params = [
            'code' => $code,
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
            'state' => 'ok',
        ];
        
        $response = $this->httpPost(self::ACCESS_TOKEN_URL, $params);
        
        $this->accessToken = $this->parseAccessToken($response);
        
        return $this->accessToken;
    }

    public function getOpenId($accessToken)
    {
        $params = ['access_token' => $accessToken];
        
        $response = $this->httpGet(self::OPENID_URL, $params);
        
        $this->openId = $this->parseOpenId($response);
        
        return $this->openId;
    }

    public function getUserInfo($accessToken, $openId)
    {
        $params = [
            'access_token' => $accessToken,
            'openid' => $openId,
            'oauth_consumer_key' => $this->appId,
        ];
        
        $response = $this->httpGet(self::USER_INFO_URL, $params);
        
        $this->parseUserInfo($response);
    }

    protected function parseAccessToken($response)
    {
        $result = [];
        
        parse_str($response, $result);
        
        if (!isset($result['access_token'])) {
            throw new \Exception("Fetch Access Token Failed:{$response}");
        }
        
        return $result['access_token'];
    }

    protected function parseOpenId($response)
    {
        $result = $match = [];
        
        if (!empty($response)) {
            preg_match('/callback\(\s+(.*?)\s+\)/i', $response, $match);
            $result = json_decode($match[1], true);
        }
        
        if (!isset($result['openid'])) {
            throw new \Exception("Fetch Openid Failed:{$response}");
        }
        
        return $result['openid'];
    }

    protected function parseUserInfo($response)
    {
        $data = json_decode($response, true);
        
        if ($data['ret'] != 0) {
            throw new \Exception("Fetch User Info Failed：{$data['msg']}");
        }
        
        $userInfo['type'] = 'QQ';
        $userInfo['name'] = $data['nickname'];
        $userInfo['nick'] = $data['nickname'];
        $userInfo['head'] = $data['figureurl_2'];
        
        return $userInfo;
    }

}
