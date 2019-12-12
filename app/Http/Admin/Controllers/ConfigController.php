<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Config as ConfigService;

/**
 * @RoutePrefix("/admin/config")
 */
class ConfigController extends Controller
{

    /**
     * @Route("/website", name="admin.config.website")
     */
    public function websiteAction()
    {
        $section = 'website';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $website = $service->getSectionConfig($section);

            $this->view->setVar('website', $website);
        }
    }

    /**
     * @Route("/secret", name="admin.config.secret")
     */
    public function secretAction()
    {
        $section = 'secret';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateStorageConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $secret = $service->getSectionConfig($section);

            $this->view->setVar('secret', $secret);
        }
    }

    /**
     * @Route("/storage", name="admin.config.storage")
     */
    public function storageAction()
    {
        $section = 'storage';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateStorageConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $storage = $service->getSectionConfig($section);

            $this->view->setVar('storage', $storage);
        }
    }

    /**
     * @Route("/vod", name="admin.config.vod")
     */
    public function vodAction()
    {
        $section = 'vod';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateVodConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $vod = $service->getSectionConfig($section);

            $this->view->setVar('vod', $vod);
        }
    }

    /**
     * @Route("/live", name="admin.config.live")
     */
    public function liveAction()
    {
        $section = 'live';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateLiveConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $live = $service->getSectionConfig($section);

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
        $service = new ConfigService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section');
            $data = $this->request->getPost();

            $service->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $alipay = $service->getSectionConfig('payment.alipay');
            $wxpay = $service->getSectionConfig('payment.wxpay');

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

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateSmserConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $smser = $service->getSectionConfig($section);

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

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $mailer = $service->getSectionConfig($section);

            $this->view->setVar('mailer', $mailer);
        }
    }

    /**
     * @Route("/captcha", name="admin.config.captcha")
     */
    public function captchaAction()
    {
        $section = 'captcha';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateSectionConfig($section, $data);

            $content = [
                'location' => $this->request->getHTTPReferer(),
                'msg' => '更新配置成功',
            ];

            return $this->ajaxSuccess($content);

        } else {

            $captcha = $service->getSectionConfig($section);

            $this->view->setVar('captcha', $captcha);
        }
    }

    /**
     * @Route("/vip", name="admin.config.vip")
     */
    public function vipAction()
    {
        $section = 'vip';

        $service = new ConfigService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $service->updateSectionConfig($section, $data);

            return $this->ajaxSuccess(['msg' => '更新配置成功']);

        } else {

            $vip = $service->getSectionConfig($section);

            $this->view->setVar('vip', $vip);
        }
    }

}
