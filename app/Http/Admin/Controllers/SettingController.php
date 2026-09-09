<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $site = $settingService->getSiteSettings();

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

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $secret = $settingService->getMySettings($section);

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

            $cos = $settingService->getMySettings($section);

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

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $vod = $settingService->getMySettings($section);

            $this->view->setVar('vod', $vod);
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

            $settingService->updateMySettings($section, $data);

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

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $sms = $settingService->getMySettings($section);

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

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $mail = $settingService->getMySettings($section);

            $this->view->setVar('mail', $mail);
        }
    }

    /**
     * @Route("/biz", name="admin.setting.biz")
     */
    public function bizAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $vod = $settingService->getMySettings('biz.vod');
            $exam = $settingService->getMySettings('biz.exam');

            $this->view->setVar('vod', $vod);
            $this->view->setVar('exam', $exam);
        }
    }

    /**
     * @Route("/security", name="admin.setting.security")
     */
    public function securityAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $throttle = $settingService->getMySettings('security.throttle');
            $blacklist = $settingService->getMySettings('security.blacklist');
            $audit = $settingService->getMySettings('security.audit');
            $piracy = $settingService->getMySettings('security.piracy');
            $register = $settingService->getMySettings('security.register');
            $access = $settingService->getMySettings('security.access');

            $this->view->setVar('throttle', $throttle);
            $this->view->setVar('blacklist', $blacklist);
            $this->view->setVar('audit', $audit);
            $this->view->setVar('piracy', $piracy);
            $this->view->setVar('register', $register);
            $this->view->setVar('access', $access);
        }
    }

    /**
     * @Route("/mobile", name="admin.setting.mobile")
     */
    public function mobileAction()
    {
        $section = 'mobile';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $tab = $this->request->getPost('tab', 'trim', 'basic');

            $settingService->updateMobileSettings($section, $tab, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $mobile = $settingService->getMySettings($section);

            $this->view->pick('setting/mobile');
            $this->view->setVar('mobile', $mobile);
        }
    }

    /**
     * @Route("/contact", name="admin.setting.contact")
     */
    public function contactAction()
    {
        $section = 'contact';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateMySettings($section, $data);

            return $this->jsonSuccess(['msg' => '更新配置成功']);

        } else {

            $contact = $settingService->getMySettings($section);

            $this->view->setVar('contact', $contact);
        }
    }

}
