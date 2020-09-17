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

            $site = $settingService->getSettings($section);

            $site['url'] = $site['url'] ?: kg_site_url();

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

            $secret = $settingService->getSettings($section);

            $this->view->setVar('secret', $secret);
        }
    }

    /**
     * @Route("/storage", name="admin.setting.storage")
     */
    public function storageAction()
    {
        $section = 'cos';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateStorageSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $cos = $settingService->getSettings($section);

            $this->view->setVar('cos', $cos);
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

            $vod = $settingService->getSettings($section);

            $this->view->setVar('vod', $vod);
        }
    }

    /**
     * @Route("/live", name="admin.setting.live")
     */
    public function liveAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateLiveSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $push = $settingService->getLiveSettings('live.push');
            $pull = $settingService->getLiveSettings('live.pull');
            $notify = $settingService->getLiveSettings('live.notify');

            $this->view->setVar('push', $push);
            $this->view->setVar('pull', $pull);
            $this->view->setVar('notify', $notify);
        }
    }

    /**
     * @Route("/pay", name="admin.setting.pay")
     */
    public function payAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $alipay = $settingService->getAlipaySettings();
            $wxpay = $settingService->getWxpaySettings();

            $this->view->setVar('alipay', $alipay);
            $this->view->setVar('wxpay', $wxpay);
        }
    }

    /**
     * @Route("/sms", name="admin.setting.sms")
     */
    public function smsAction()
    {
        $section = 'sms';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSmsSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $sms = $settingService->getSettings($section);

            $this->view->setVar('sms', $sms);
        }
    }

    /**
     * @Route("/mail", name="admin.setting.mail")
     */
    public function mailAction()
    {
        $section = 'mail';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $mail = $settingService->getSettings($section);

            $this->view->setVar('mail', $mail);
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

            $captcha = $settingService->getSettings($section);

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

            $data = $this->request->getPost('vip', 'string');

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

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateSectionSettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $main = $settingService->getSettings('im.main');
            $cs = $settingService->getSettings('im.cs');

            $this->view->setVar('main', $main);
            $this->view->setVar('cs', $cs);
        }
    }

}
