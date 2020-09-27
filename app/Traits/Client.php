<?php

namespace App\Traits;

use App\Models\Client as ClientModel;
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

        $clientType = ClientModel::TYPE_DESKTOP;

        if ($result->isMobile()) {
            $clientType = ClientModel::TYPE_MOBILE;
        }

        return $clientType;
    }

}