<?php

namespace App\Traits;

use App\Services\Throttle;
use Phalcon\Di;
use Phalcon\Http\Request;

trait Security
{

    public function checkCsrfToken()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $tokenKey = $request->getHeader('X-Csrf-Token-Key');
        $tokenValue = $request->getHeader('X-Csrf-Token-Value');

        /**
         * @var \App\Library\Security $security
         */
        $security = Di::getDefault()->get('security');

        return $security->checkToken($tokenKey, $tokenValue);
    }

    public function checkHttpReferer()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $httpHost = parse_url($request->getHttpReferer(), PHP_URL_HOST);

        return $httpHost == $request->getHttpHost();
    }

    public function checkRateLimit()
    {
        $throttle = new Throttle();

        return $throttle->checkRateLimit();
    }

    public function isNotSafeRequest()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $method = $request->getMethod();

        $list = ['post', 'put', 'patch', 'delete'];

        return in_array(strtolower($method), $list);
    }

}