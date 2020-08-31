<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Setting as SettingService;

/**
 * @RoutePrefix("/admin/setting")
 */
class SettingController extends Controller
{

    /**
     * @Route("/site", name="admin.setting.site")
     */
    public function siteAction()
    {
        $section = 'site';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $site = $settingService->getSectionSettings($section);

            $site->base_url = $site->base_url ?: kg_site_base_url();

            $this->view->setVar('site', $site);
        }
    }

    /**
     * @Route("/secret", name="admin.setting.secret")
     */
    public function secretAction()
    {
        $section = 'secret';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateStorageSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $secret = $settingService->getSectionSettings($section);

            $this->view->setVar('secret', $secret);
        }
    }

    /**
     * @Route("/storage", name="admin.setting.storage")
     */
    public function storageAction()
    {
        $section = 'storage';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateStorageSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $storage = $settingService->getSectionSettings($section);

            $this->view->setVar('storage', $storage);
        }
    }

    /**
     * @Route("/vod", name="admin.setting.vod")
     */
    public function vodAction()
    {
        $section = 'vod';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateVodSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $vod = $settingService->getSectionSettings($section);

            $this->view->setVar('vod', $vod);
        }
    }

    /**
     * @Route("/live", name="admin.setting.live")
     */
    public function liveAction()
    {
        $section = 'live';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateLiveSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $live = $settingService->getLiveSettings();

            $this->view->setVar('live', $live);
        }
    }

    /**
     * @Route("/pay", name="admin.setting.pay")
     */
    public function payAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section');

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $alipay = $settingService->getSectionSettings('pay.alipay');

            $alipay->notify_url = $alipay->notify_url ?: kg_full_url(['for' => 'desktop.alipay_notify']);

            $wxpay = $settingService->getSectionSettings('pay.wxpay');

            $wxpay->notify_url = $wxpay->notify_url ?: kg_full_url(['for' => 'desktop.wxpay_notify']);

            $this->view->setVar('alipay', $alipay);
            $this->view->setVar('wxpay', $wxpay);
        }
    }

    /**
     * @Route("/smser", name="admin.setting.smser")
     */
    public function smserAction()
    {
        $section = 'smser';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSmserSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $smser = $settingService->getSectionSettings($section);

            $template = json_decode($smser->template);

            $this->view->setVar('smser', $smser);
            $this->view->setVar('template', $template);
        }
    }

    /**
     * @Route("/mailer", name="admin.setting.mailer")
     */
    public function mailerAction()
    {
        $section = 'mailer';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $mailer = $settingService->getSectionSettings($section);

            $this->view->setVar('mailer', $mailer);
        }
    }

    /**
     * @Route("/captcha", name="admin.setting.captcha")
     */
    public function captchaAction()
    {
        $section = 'captcha';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            $content = [
                'location' => $this->request->getHTTPReferer(),
                'msg' => '更新配置成功',
            ];

            return $this->jsonSuccess($content);

        } else {

            $captcha = $settingService->getSectionSettings($section);

            $this->view->setVar('captcha', $captcha);
        }
    }

    /**
     * @Route("/vip", name="admin.setting.vip")
     */
    public function vipAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost('vip');

            $settingService->updateVipSettings($data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $vips = $settingService->getVipSettings();

            $this->view->setVar('vips', $vips);
        }
    }

    /**
     * @Route("/im", name="admin.setting.im")
     */
    public function imAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section');

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $main = $settingService->getSectionSettings('im.main');
            $cs = $settingService->getSectionSettings('im.cs');

            $this->view->setVar('main', $main);
            $this->view->setVar('cs', $cs);
        }
    }

}
