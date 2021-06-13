<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use Phalcon\Crypt;
use Phalcon\Di;

abstract class OAuth extends Service
{

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
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

        $text = rand(1000, 9999);

        return $crypt->encryptBase64($text, null, true);
    }

    public function checkState($state)
    {
        /**
         * @var $crypt Crypt
         */
        $crypt = Di::getDefault()->get('crypt');

        $value = $crypt->decryptBase64($state, null, true);

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
