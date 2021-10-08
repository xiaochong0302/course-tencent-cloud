<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\OAuth;

use App\Models\Connect as ConnectModel;
use App\Services\OAuth;

class WeiBo extends OAuth
{

    const AUTHORIZE_URL = 'https://api.weibo.com/oauth2/authorize';
    const ACCESS_TOKEN_URL = 'https://api.weibo.com/oauth2/access_token';
    const USER_INFO_URL = 'https://api.weibo.com/2/users/show.json';

    public function getAuthorizeUrl()
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->getState(),
            'response_type' => 'code',
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

        if (!isset($data['access_token']) || !isset($data['uid'])) {
            throw new \Exception("Fetch Access Token Failed:{$response}");
        }

        $this->openId = $data['uid'];

        return $data['access_token'];
    }

    private function parseUserInfo($response)
    {
        $data = json_decode($response, true);

        if (isset($data['error_code']) && $data['error_code'] != 0) {
            throw new \Exception("Fetch User Info Failed:{$response}");
        }

        $userInfo['id'] = $data['id'];
        $userInfo['name'] = $data['name'];
        $userInfo['avatar'] = $data['profile_image_url'];
        $userInfo['provider'] = ConnectModel::PROVIDER_WEIBO;
        $userInfo['unionid'] = '';

        return $userInfo;
    }

}
