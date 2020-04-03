<?php

namespace App\Traits;

use Phalcon\Di;
use Phalcon\Http\Request;
use WhichBrowser\Parser as BrowserParser;

trait Client
{

    public function getClientIp()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        return $request->getClientAddress();
    }

    public function getClientType()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        $clientType = 'desktop';

        if ($result->isMobile()) {
            $clientType = 'mobile';
        }

        return $clientType;
    }

}