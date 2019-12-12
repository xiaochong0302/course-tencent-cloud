<?php

namespace App\Traits;

trait Security
{

    public function checkCsrfToken()
    {
        $tokenKey = $this->request->getHeader('X-Csrf-Token-Key');
        $tokenValue = $this->request->getHeader('X-Csrf-Token-Value');
        $checkToken = $this->security->checkToken($tokenKey, $tokenValue);

        return $checkToken;
    }

    public function checkHttpReferer()
    {
        $httpHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $checkHost = $httpHost == $this->request->getHttpHost();

        return $checkHost;
    }

    public function notSafeRequest()
    {
        $method = $this->request->getMethod();

        $whitelist = ['post', 'put', 'patch', 'delete'];

        $result = in_array(strtolower($method), $whitelist);

        return $result;

    }

}