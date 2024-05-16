<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Http\Api\Services\Connect as ConnectService;
use App\Models\Connect as ConnectModel;

/**
 * @RoutePrefix("/api/oauth")
 */
class ConnectController extends Controller
{

    /**
     * @Get("/wechat", name="api.oauth.wechat")
     */
    public function wechatAction()
    {
        $redirect = $this->request->getQuery('redirect', 'trim');

        $service = new ConnectService();

        if ($redirect) {
            $service->setWechatRedirectCache($redirect);
        }

        $url = $service->getAuthorizeUrl(ConnectModel::PROVIDER_WECHAT_OA);

        return $this->response->redirect($url, true);
    }

    /**
     * @Get("/wechat/callback", name="api.oauth.wechat_callback")
     */
    public function wechatCallbackAction()
    {
        $service = new ConnectService();

        $data = $service->handleCallback(ConnectModel::PROVIDER_WECHAT_OA);

        $redirect = $service->getWechatRedirectCache();

        if ($redirect) {
            $service->removeWechatRedirectCache();
            if (strpos($redirect, '?')) {
                $location = $redirect . '&' . http_build_query($data);
            } else {
                $location = $redirect . '?' . http_build_query($data);
            }
        } else {
            $location = kg_h5_index_url() . '?' . http_build_query($data);
        }

        return $this->response->redirect($location, true);
    }

    /**
     * @Get("/wechat/info", name="api.oauth.wechat_info")
     */
    public function wechatInfoAction()
    {
        $service = new ConnectService();

        $wechat = $service->getWechatInfo();

        return $this->jsonSuccess(['wechat' => $wechat]);
    }

}
