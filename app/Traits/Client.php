<?php

namespace App\Traits;

use WhichBrowser\Parser as BrowserParser;

trait Client
{

    public function getClientIp()
    {
        $clientIp = $this->request->getClientAddress();

        return $clientIp;
    }

    public function getClientType()
    {
        $userAgent = $this->request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        $clientType = 'desktop';

        if ($result->isMobile()) {
            $clientType = 'mobile';
        }

        return $clientType;
    }

}