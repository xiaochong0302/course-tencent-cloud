<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\OAuth;

use App\Models\Connect as ConnectModel;
use App\Services\OAuth;

class QQ extends OAuth
{

    const AUTHORIZE_URL = 'https://graph.qq.com/oauth2.0/authorize';
    const ACCESS_TOKEN_URL = 'https://graph.qq.com/oauth2.0/token';
    const OPENID_URL = 'https://graph.qq.com/oauth2.0/me';
    const USER_INFO_URL = 'https://graph.qq.com/user/get_user_info';

    public function getAuthorizeUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->getState(),
            'response_type' => 'code',
            'scope' => 'get_user_info',
        ];

        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    public function getAccessToken($code)
    {
        $params = [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
        ];

        $response = $this->httpGet(self::ACCESS_TOKEN_URL, $params);

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
            'oauth_consumer_key' => $this->clientId,
            'access_token' => $accessToken,
            'openid' => $openId,
        ];

        $response = $this->httpGet(self::USER_INFO_URL, $params);

        return $this->parseUserInfo($response);
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
        $result = $matches = [];

        if (!empty($response)) {
            preg_match('/callback\(\s+(.*?)\s+\)/i', $response, $matches);
            $result = json_decode($matches[1], true);
        }

        if (!isset($result['openid'])) {
            throw new \Exception("Fetch OpenId Failed:{$response}");
        }

        return $result['openid'];
    }

    protected function parseUserInfo($response)
    {
        $data = json_decode($response, true);

        if (isset($data['ret']) && $data['ret'] != 0) {
            throw new \Exception("Fetch User Info Failed:{$response}");
        }

        $userInfo['id'] = $this->openId;
        $userInfo['name'] = $data['nickname'];
        $userInfo['avatar'] = $data['figureurl'];
        $userInfo['provider'] = ConnectModel::PROVIDER_QQ;
        $userInfo['unionid'] = '';

        return $userInfo;
    }

}
