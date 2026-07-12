<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Models\KgClient as KgClientModel;
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

        $types = array_flip(KgClientModel::types());

        if (!empty($platform) && isset($types[$platform])) {
            return $types[$platform];
        }

        $userAgent = $request->getServer('HTTP_USER_AGENT');

        $result = new BrowserParser($userAgent);

        $clientType = KgClientModel::TYPE_PC;

        if ($result->isMobile() || $this->isHarmonyMobile($userAgent)) {
            $clientType = KgClientModel::TYPE_H5;
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

        return $result->isMobile() || $this->isHarmonyMobile($userAgent);
    }

    protected function isHarmonyMobile($userAgent)
    {
        $userAgent = strtolower($userAgent);

        if (strpos($userAgent, 'harmony') === false) return false;

        $keywords = ['mobile', 'phone', 'tablet'];

        foreach ($keywords as $keyword) {
            if (strpos($userAgent, $keyword)) return true;
        }

        return false;
    }

    public function h5Enabled()
    {
        $file = public_path('h5/index.html');

        return file_exists($file);
    }

}
