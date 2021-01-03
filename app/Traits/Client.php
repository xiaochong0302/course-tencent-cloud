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

        $platform = $request->getHeader('X-Platform');

        $types = array_flip(ClientModel::types());

        if (!empty($platform) && isset($types[$platform])) {
            return $types[$platform];
        }

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        $clientType = ClientModel::TYPE_PC;

        if ($result->isMobile()) {
            $clientType = ClientModel::TYPE_H5;
        }

        return $clientType;
    }

    public function isMobileBrowser()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        return $result->isMobile();
    }

}