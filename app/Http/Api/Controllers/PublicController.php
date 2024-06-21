<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Vip\OptionList as VipOptionList;
use App\Services\Service as AppService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/api")
 */
class PublicController extends Controller
{

    use ResponseTrait;

    /**
     * @Options("/{match:(.*)}", name="api.match_options")
     */
    public function corsAction()
    {
        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @Get("/now", name="api.public.now")
     */
    public function nowAction()
    {
        return $this->jsonSuccess(['now' => time()]);
    }

    /**
     * @Get("/socket/info", name="api.public.socket_info")
     */
    public function socketInfoAction()
    {
        $service = new AppService();

        $websocket = $service->getConfig()->get('websocket');

        $content = [];

        /**
         * ssl通过nginx转发实现
         */
        if ($this->request->isSecure()) {
            list($domain) = explode(':', $websocket->connect_address);
            $content['connect_url'] = sprintf('wss://%s/wss', $domain);
        } else {
            $content['connect_url'] = sprintf('ws://%s', $websocket->connect_address);
        }

        $content['ping_interval'] = $websocket->ping_interval;

        return $this->jsonSuccess(['socket' => $content]);
    }

    /**
     * @Get("/site/info", name="api.public.site_info")
     */
    public function siteInfoAction()
    {
        $service = new AppService();

        $site = $service->getSettings('site');

        return $this->jsonSuccess(['site' => $site]);
    }

    /**
     * @Get("/payment/info", name="api.public.payment_info")
     */
    public function paymentInfoAction()
    {
        $service = new AppService();

        $alipay = $service->getSettings('pay.alipay');
        $wxpay = $service->getSettings('pay.wxpay');

        $content = [
            'alipay' => ['enabled' => $alipay['enabled']],
            'wxpay' => ['enabled' => $wxpay['enabled']],
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/vip/options", name="api.public.vip_options")
     */
    public function vipOptionsAction()
    {
        $service = new VipOptionList();

        $options = $service->handle();

        return $this->jsonSuccess(['options' => $options]);
    }

}
