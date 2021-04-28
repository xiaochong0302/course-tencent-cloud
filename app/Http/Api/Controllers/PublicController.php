<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Reward\OptionList as RewardOptionList;
use App\Services\Logic\User\Console\NotifyStats as NotifyStatsService;
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

        if ($this->request->isSecure()) {
            $content['connect_url'] = sprintf('wss://%s/wss', $this->request->getHttpHost());
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
     * @Get("/captcha/info", name="api.public.captcha_info")
     */
    public function captchaInfoAction()
    {
        $service = new AppService();

        $captcha = $service->getSettings('captcha');

        unset($captcha['secret_key']);

        return $this->jsonSuccess(['captcha' => $captcha]);
    }

    /**
     * @Get("/reward/options", name="api.public.reward_options")
     */
    public function rewardOptionsAction()
    {
        $service = new RewardOptionList();

        $options = $service->handle();

        return $this->jsonSuccess(['options' => $options]);
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

    /**
     * @Get("/notify/stats", name="home.notify_stats")
     */
    public function notifyStatsAction()
    {
        $service = new NotifyStatsService();

        $stats = $service->handle();

        return $this->jsonSuccess(['stats' => $stats]);
    }

}
