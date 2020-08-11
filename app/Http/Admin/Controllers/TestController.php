<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AlipayTest as AlipayTestService;
use App\Http\Admin\Services\Setting as SettingService;
use App\Http\Admin\Services\WxpayTest as WxpayTestService;
use App\Services\Captcha as CaptchaService;
use App\Services\Live as LiveService;
use App\Services\Mailer\Test as TestMailerService;
use App\Services\MyStorage as StorageService;
use App\Services\Smser\Test as TestSmserService;
use App\Services\Vod as VodService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin/test")
 */
class TestController extends Controller
{

    /**
     * @Post("/storage", name="admin.test.storage")
     */
    public function storageAction()
    {
        $storageService = new StorageService();

        $result = [];

        $result['hello'] = $storageService->uploadTestFile();
        $result['avatar'] = $storageService->uploadDefaultAvatarImage();
        $result['cover'] = $storageService->uploadDefaultCoverImage();

        if ($result['hello'] && $result['avatar'] && $result['cover']) {
            return $this->jsonSuccess(['msg' => '上传文件成功，请到控制台确认']);
        } else {
            return $this->jsonError(['msg' => '上传文件失败，请检查相关配置']);
        }
    }

    /**
     * @Post("/vod", name="admin.test.vod")
     */
    public function vodAction()
    {
        $vodService = new VodService();

        $result = $vodService->test();

        if ($result) {
            return $this->jsonSuccess(['msg' => '接口返回成功']);
        } else {
            return $this->jsonError(['msg' => '接口返回失败，请检查相关配置']);
        }
    }

    /**
     * @Get("/live/push", name="admin.test.live_push")
     */
    public function livePushAction()
    {
        $streamName = $this->request->getQuery('stream');

        $liveService = new LiveService();

        $pushUrl = $liveService->getPushUrl($streamName);

        $qrcode = $this->url->get(
            ['for' => 'web.qrcode'],
            ['text' => urlencode($pushUrl)]
        );

        $pos = strrpos($pushUrl, '/');

        $obs = [
            'fms_url' => substr($pushUrl, 0, $pos + 1),
            'stream_code' => substr($pushUrl, $pos + 1),
        ];

        $this->view->pick('setting/live_push_test');
        $this->view->setVar('qrcode', $qrcode);
        $this->view->setVar('obs', $obs);
    }

    /**
     * @Get("/live/pull", name="admin.test.live_pull")
     */
    public function livePullAction()
    {
        $liveService = new LiveService();

        $pullUrls = $liveService->getPullUrls('test');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('public/live_player');
        $this->view->setVar('pull_urls', $pullUrls);
    }

    /**
     * @Post("/smser", name="admin.test.smser")
     */
    public function smserAction()
    {
        $phone = $this->request->getPost('phone');

        $smserService = new TestSmserService();

        $response = $smserService->handle($phone);

        if ($response) {
            return $this->jsonSuccess(['msg' => '发送短信成功，请到收件箱确认']);
        } else {
            return $this->jsonError(['msg' => '发送短信失败，请查看短信日志']);
        }
    }

    /**
     * @Post("/mailer", name="admin.test.mailer")
     */
    public function mailerAction()
    {
        $email = $this->request->getPost('email');

        $mailerService = new TestMailerService();

        $result = $mailerService->handle($email);

        if ($result) {
            return $this->jsonSuccess(['msg' => '发送邮件成功，请到收件箱确认']);
        } else {
            return $this->jsonError(['msg' => '发送邮件失败，请检查配置']);
        }
    }

    /**
     * @Post("/captcha", name="admin.test.captcha")
     */
    public function captchaAction()
    {
        $post = $this->request->getPost();

        $captchaService = new CaptchaService();

        $result = $captchaService->verify($post['ticket'], $post['rand']);

        if ($result) {

            $settingService = new SettingService();

            $settingService->updateSectionSettings('captcha', ['enabled' => 1]);

            return $this->jsonSuccess(['msg' => '后台验证成功']);

        } else {
            return $this->jsonError(['msg' => '后台验证失败']);
        }
    }

    /**
     * @Get("/alipay", name="admin.test.alipay")
     */
    public function alipayAction()
    {
        $alipayTestService = new AlipayTestService();

        $this->db->begin();

        $order = $alipayTestService->createOrder();
        $trade = $alipayTestService->createTrade($order);
        $qrcode = $alipayTestService->scan($trade);

        if ($order && $trade && $qrcode) {
            $this->db->commit();
        } else {
            $this->db->rollback();
        }

        $this->view->pick('setting/pay_alipay_test');
        $this->view->setVar('sn', $trade->sn);
        $this->view->setVar('qrcode', $qrcode);
    }

    /**
     * @Get("/alipay/status", name="admin.test.alipay_status")
     */
    public function alipayStatusAction()
    {
        $sn = $this->request->getQuery('sn');

        $alipayTestService = new AlipayTestService();

        $status = $alipayTestService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/alipay/cancel", name="admin.test.alipay_cancel")
     */
    public function alipayCancelAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayTestService = new AlipayTestService();

        $alipayTestService->cancel($sn);

        return $this->jsonSuccess(['msg' => '取消订单成功']);
    }

    /**
     * @Get("/wxpay", name="admin.test.wxpay")
     */
    public function wxpayAction()
    {
        $wxpayTestService = new WxpayTestService();

        $this->db->begin();

        $order = $wxpayTestService->createOrder();
        $trade = $wxpayTestService->createTrade($order);
        $qrcode = $wxpayTestService->scan($trade);

        if ($order && $trade && $qrcode) {
            $this->db->commit();
        } else {
            $this->db->rollback();
        }

        $this->view->pick('setting/pay_wxpay_test');
        $this->view->setVar('sn', $trade->sn);
        $this->view->setVar('qrcode', $qrcode);
    }

    /**
     * @Get("/wxpay/status", name="admin.test.wxpay_status")
     */
    public function wxpayStatusAction()
    {
        $sn = $this->request->getQuery('sn');

        $wxpayTestService = new WxpayTestService();

        $status = $wxpayTestService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/wxpay/cancel", name="admin.test.wxpay_cancel")
     */
    public function wxpayCancelAction()
    {
        $sn = $this->request->getPost('sn');

        $wxpayTestService = new WxpayTestService();

        $wxpayTestService->cancel($sn);

        return $this->jsonSuccess(['msg' => '取消订单成功']);
    }

}
