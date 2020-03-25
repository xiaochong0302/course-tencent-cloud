<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Config as ConfigService;

/**
 * @RoutePrefix("/admin/config")
 */
class ConfigController extends Controller
{

    /**
     * @Route("/site", name="admin.config.site")
     */
    public function siteAction()
    {
        $section = 'site';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $site = $configService->getSectionConfig($section);

            $this->view->setVar('site', $site);
        }
    }

    /**
     * @Route("/secret", name="admin.config.secret")
     */
    public function secretAction()
    {
        $section = 'secret';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateStorageConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $secret = $configService->getSectionConfig($section);

            $this->view->setVar('secret', $secret);
        }
    }

    /**
     * @Route("/storage", name="admin.config.storage")
     */
    public function storageAction()
    {
        $section = 'storage';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateStorageConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $storage = $configService->getSectionConfig($section);

            $this->view->setVar('storage', $storage);
        }
    }

    /**
     * @Route("/vod", name="admin.config.vod")
     */
    public function vodAction()
    {
        $section = 'vod';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateVodConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $vod = $configService->getSectionConfig($section);

            $this->view->setVar('vod', $vod);
        }
    }

    /**
     * @Route("/live", name="admin.config.live")
     */
    public function liveAction()
    {
        $section = 'live';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateLiveConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $live = $configService->getSectionConfig($section);

            $ptt = json_decode($live->pull_trans_template);

            $this->view->setVar('live', $live);
            $this->view->setVar('ptt', $ptt);
        }
    }

    /**
     * @Route("/payment", name="admin.config.payment")
     */
    public function paymentAction()
    {
        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section');

            $data = $this->request->getPost();

            $configService->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $alipay = $configService->getSectionConfig('payment.alipay');
            $wxpay = $configService->getSectionConfig('payment.wxpay');

            $this->view->setVar('alipay', $alipay);
            $this->view->setVar('wxpay', $wxpay);
        }
    }

    /**
     * @Route("/smser", name="admin.config.smser")
     */
    public function smserAction()
    {
        $section = 'smser';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateSmserConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $smser = $configService->getSectionConfig($section);

            $template = json_decode($smser->template);

            $this->view->setVar('smser', $smser);
            $this->view->setVar('template', $template);
        }
    }

    /**
     * @Route("/mailer", name="admin.config.mailer")
     */
    public function mailerAction()
    {
        $section = 'mailer';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $mailer = $configService->getSectionConfig($section);

            $this->view->setVar('mailer', $mailer);
        }
    }

    /**
     * @Route("/captcha", name="admin.config.captcha")
     */
    public function captchaAction()
    {
        $section = 'captcha';

        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $configService->updateSectionConfig($section, $data);

            $content = [
                'location' => $this->request->getHTTPReferer(),
                'msg' => '更新配置成功',
            ];

            return $this->ajaxSuccess($content);

        } else {

            $captcha = $configService->getSectionConfig($section);

            $this->view->setVar('captcha', $captcha);
        }
    }

    /**
     * @Route("/vip", name="admin.config.vip")
     */
    public function vipAction()
    {
        $configService = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost('vip');

            $configService->updateVipConfig($data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $vips = $configService->getVipConfig();

            $this->view->setVar('vips', $vips);
        }
    }

}
