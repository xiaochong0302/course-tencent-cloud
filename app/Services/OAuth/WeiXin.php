<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\OAuth;

use App\Models\Connect as ConnectModel;
use App\Services\OAuth;

class WeiXin extends OAuth
{

    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/qrconnect';
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    const USER_INFO_URL = 'https://api.weixin.qq.com/sns/userinfo';

    public function getAuthorizeUrl()
    {
        $params = [
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->getState(),
            'response_type' => 'code',
            'scope' => 'snsapi_login',
        ];

        return self::AUTHORIZE_URL . '?' . http_build_query($params);
    }

    public function getAccessToken($code)
    {
        $params = [
            'code' => $code,
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
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
            throw new \Exception("Fetch Access Token Failed:{$response}");
        }

        $this->openId = $data['openid'];

        return $data['access_token'];
    }

    private function parseUserInfo($response)
    {
        $data = json_decode($response, true);

        if (isset($data['errcode']) && $data['errcode'] != 0) {
            throw new \Exception("Fetch User Info Failed:{$response}");
        }

        $userInfo['id'] = $data['openid'];
        $userInfo['name'] = $data['nickname'];
        $userInfo['avatar'] = $data['headimgurl'];
        $userInfo['unionid'] = $data['unionid'] ?? '';
        $userInfo['provider'] = ConnectModel::PROVIDER_WEIXIN;

        return $userInfo;
    }

}
