<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AlipayTest as AlipayTestService;
use App\Http\Admin\Services\Config as ConfigService;
use App\Services\Captcha as CaptchaService;
use App\Services\Live as LiveService;
use App\Services\Mailer as MailerService;
use App\Services\Smser as SmserService;
use App\Services\Storage as StorageService;
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
    public function storageTestAction()
    {
        $storageService = new StorageService();

        $result = $storageService->uploadTestFile();

        if ($result) {
            return $this->ajaxSuccess(['msg' => '上传文件成功，请到控制台确认']);
        } else {
            return $this->ajaxError(['msg' => '上传文件失败，请检查相关配置']);
        }
    }

    /**
     * @Post("/vod", name="admin.test.vod")
     */
    public function vodTestAction()
    {
        $vodService = new VodService();

        $result = $vodService->test();

        if ($result) {
            return $this->ajaxSuccess(['msg' => '接口返回成功']);
        } else {
            return $this->ajaxError(['msg' => '接口返回失败，请检查相关配置']);
        }
    }

    /**
     * @Get("/live/push", name="admin.test.live.push")
     */
    public function livePushTestAction()
    {
        $liveService = new LiveService();

        $pushUrl = $liveService->getPushUrl('test');

        $obs = new \stdClass();

        $position = strrpos($pushUrl, '/');
        $obs->fms_url = substr($pushUrl, 0, $position + 1);
        $obs->stream_code = substr($pushUrl, $position + 1);

        $this->view->pick('config/live_push_test');
        $this->view->setVar('push_url', $pushUrl);
        $this->view->setVar('obs', $obs);
    }

    /**
     * @Get("/live/pull", name="admin.test.live.pull")
     */
    public function livePullTestAction()
    {
        $liveService = new LiveService();

        $m3u8PullUrls = $liveService->getPullUrls('test', 'm3u8');
        $flvPullUrls = $liveService->getPullUrls('test', 'flv');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('public/live_player');
        $this->view->setVar('m3u8_pull_urls', $m3u8PullUrls);
        $this->view->setVar('flv_pull_urls', $flvPullUrls);
    }

    /**
     * @Post("/smser", name="admin.test.smser")
     */
    public function smserTestAction()
    {
        $phone = $this->request->getPost('phone');

        $smserService = new SmserService();

        $response = $smserService->sendTestMessage($phone);

        if ($response) {
            return $this->ajaxSuccess(['msg' => '发送短信成功，请到收件箱确认']);
        } else {
            return $this->ajaxError(['msg' => '发送短信失败，请查看短信日志']);
        }
    }

    /**
     * @Post("/mailer", name="admin.test.mailer")
     */
    public function mailerTestAction()
    {
        $email = $this->request->getPost('email');

        $mailerService = new MailerService();

        $result = $mailerService->sendTestMail($email);

        if ($result) {
            return $this->ajaxSuccess(['msg' => '发送邮件成功，请到收件箱确认']);
        } else {
            return $this->ajaxError(['msg' => '发送邮件失败，请检查配置']);
        }
    }

    /**
     * @Post("/captcha", name="admin.test.captcha")
     */
    public function captchaTestAction()
    {
        $post = $this->request->getPost();

        $captchaService = new CaptchaService();

        $result = $captchaService->verify($post['ticket'], $post['rand']);

        if ($result) {

            $configService = new ConfigService();

            $configService->updateSectionConfig('captcha', ['enabled' => 1]);

            return $this->ajaxSuccess(['msg' => '后台验证成功']);

        } else {
            return $this->ajaxError(['msg' => '后台验证失败']);
        }
    }

    /**
     * @Get("/alipay", name="admin.test.alipay")
     */
    public function alipayTestAction()
    {
        $alipayTestService = new AlipayTestService();

        $this->db->begin();

        $order = $alipayTestService->createTestOrder();
        $trade = $alipayTestService->createTestTrade($order);
        $qrcode = $alipayTestService->getTestQrCode($trade);

        if ($order->id > 0 && $trade->id > 0 && $qrcode) {
            $this->db->commit();
        } else {
            $this->db->rollback();
        }

        $this->view->pick('config/payment_alipay_test');
        $this->view->setVar('trade', $trade);
        $this->view->setVar('qrcode', $qrcode);
    }

    /**
     * @Post("/alipay/status", name="admin.test.alipay.status")
     */
    public function alipayTestStatusAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayTestService = new AlipayTestService();

        $status = $alipayTestService->getTestStatus($sn);

        return $this->ajaxSuccess(['status' => $status]);
    }

    /**
     * @Post("/alipay/cancel", name="admin.test.alipay.cancel")
     */
    public function alipayTestCancelAction()
    {
        $sn = $this->request->getPost('sn');

        $alipayTestService = new AlipayTestService();

        $alipayTestService->cancelTestOrder($sn);

        return $this->ajaxSuccess(['msg' => '取消订单成功']);
    }

}
