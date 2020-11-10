<?php

namespace App\Validators;

use App\Caches\App as AppCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\App as AppModel;

class ApiSecurity extends Validator
{

    public function check()
    {
        $query = $this->request->getQuery();

        if (isset($query['_url'])) {
            unset($query['_url']);
        }

        $extra = [
            '_timestamp' => $this->checkTimestamp(),
            '_nonce' => $this->checkNonce(),
        ];

        $appKey = $this->checkAppKey();

        $app = $this->getApp($appKey);

        if (!$app || $app->published == 0) {
            throw new BadRequestException('api.invalid_app_key');
        }

        $url = $this->getRequestUrl();

        if ($this->request->getMethod() == 'POST') {
            $mySignature = $this->httpPostSignature($url, $extra, $app->secret);
        } else {
            $params = array_merge($query, $extra);
            $mySignature = $this->httpGetSignature($url, $params, $app->secret);
        }

        $signature = $this->request->getHeader('X-Signature');

        if ($signature != $mySignature) {
            throw new BadRequestException('api.invalid_signature');
        }

        return $signature;
    }

    protected function checkTimestamp()
    {
        $timestamp = $this->request->getHeader('X-Timestamp');

        $timestamp = $timestamp > 0 ? $timestamp : 0;

        if (abs(time() - $timestamp) > 300) {
            throw new BadRequestException('api.invalid_timestamp');
        }

        return $timestamp;
    }

    protected function checkNonce()
    {
        $nonce = $this->request->getHeader('X-Nonce');

        if (!$nonce) {
            throw new BadRequestException('api.invalid_nonce');
        }

        return $nonce;
    }

    protected function checkAppKey()
    {
        $appKey = $this->request->getHeader('X-App-Key');

        if (!$appKey) {
            throw new BadRequestException('api.invalid_app_key');
        }

        return $appKey;
    }

    protected function checkPlatform()
    {
        $platform = $this->request->getHeader('X-Platform');

        if (!array_key_exists($platform, AppModel::types())) {
            throw new BadRequestException('api.invalid_platform');
        }

        return $platform;
    }

    protected function getRequestUrl()
    {
        return sprintf('%s://%s%s',
            $this->request->getScheme(),
            $this->request->getHttpHost(),
            $this->request->getURI()
        );
    }

    protected function getApp($appKey)
    {
        $cache = new AppCache();

        return $cache->get($appKey);
    }

    protected function httpGetSignature($url, $params, $appSecret)
    {
        ksort($params);

        $query = http_build_query($params);

        return md5($url . $query . $appSecret);
    }

    protected function httpPostSignature($url, $params, $appSecret)
    {
        ksort($params);

        $query = http_build_query($params);

        $body = $this->request->getRawBody();

        return md5($url . $query . $body . $appSecret);
    }

}
