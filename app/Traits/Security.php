<?php

namespace App\Traits;

use Phalcon\Di;
use Phalcon\Http\Request;

trait Security
{

    public function checkCsrfToken()
    {
        /**
         * @var Request $request ;
         */
        $request = Di::getDefault()->get('request');

        $tokenKey = $request->getHeader('X-Csrf-Token-Key');
        $tokenValue = $request->getHeader('X-Csrf-Token-Value');

        /**
         * @var \App\Library\Security $security
         */
        $security = Di::getDefault()->get('security');

        $checkToken = $security->checkToken($tokenKey, $tokenValue);

        return $checkToken;
    }

    public function checkHttpReferer()
    {
        /**
         * @var Request $request ;
         */
        $request = Di::getDefault()->get('request');

        $httpHost = parse_url($request->getHttpReferer(), PHP_URL_HOST);

        $checkHost = $httpHost == $request->getHttpHost();

        return $checkHost;
    }

    public function isNotSafeRequest()
    {
        /**
         * @var Request $request ;
         */
        $request = Di::getDefault()->get('request');

        $method = $request->getMethod();

        $list = ['post', 'put', 'patch', 'delete'];

        $result = in_array(strtolower($method), $list);

        return $result;
    }

}