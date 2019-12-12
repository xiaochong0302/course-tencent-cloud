<?php

namespace App\Traits;

use WhichBrowser\Parser as BrowserParser;

trait Client
{

    public function getClientIp()
    {
        return $this->request->getClientAddress();
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