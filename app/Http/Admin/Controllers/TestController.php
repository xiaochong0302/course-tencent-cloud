<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AlipayTest as AlipayTestService;
use App\Http\Admin\Services\WxpayTest as WxpayTestService;
use App\Services\DingTalkNotice as DingTalkNoticeService;
use App\Services\Live as LiveService;
use App\Services\Logic\Notice\External\Mail\Test as MailTestService;
use App\Services\Logic\Notice\External\Sms\Test as SmsTestService;
use App\Services\MyStorage as StorageService;
use App\Services\Vod as VodService;
use App\Services\WeChat as WeChatService;

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

        $result = $storageService->uploadTestFile();

        if ($result) {
            return $this->jsonSuccess(['msg' => '上传文件成功']);
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
     * @Post("/wechat/oa", name="admin.test.wechat_oa")
     */
    public function wechatOaAction()
    {
        $wechatService = new WeChatService();

        $oa = $wechatService->getOfficialAccount();

        $result = $oa->qrcode->temporary('foo', 86400);

        if (isset($result['ticket'])) {
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
        $streamName = $this->request->getQuery('stream', 'string');

        $liveService = new LiveService();

        $pushUrl = $liveService->getPushUrl($streamName);

        $qrcode = $this->url->get(
            ['for' => 'home.qrcode'],
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

        $this->view->pick('public/live_player');
        $this->view->setVar('pull_urls', $pullUrls);
    }

    /**
     * @Post("/sms", name="admin.test.sms")
     */
    public function smsAction()
    {
        $phone = $this->request->getPost('phone', 'string');

        $smsService = new SmsTestService();

        $response = $smsService->handle($phone);

        if ($response) {
            return $this->jsonSuccess(['msg' => '发送短信成功，请到收件箱确认']);
        } else {
            return $this->jsonError(['msg' => '发送短信失败，请查看短信日志']);
        }
    }

    /**
     * @Post("/mail", name="admin.test.mail")
     */
    public function mailAction()
    {
        $email = $this->request->getPost('email', 'string');

        $mailService = new MailTestService();

        $result = $mailService->handle($email);

        if ($result) {
            return $this->jsonSuccess(['msg' => '发送邮件成功，请到收件箱确认']);
        } else {
            return $this->jsonError(['msg' => '发送邮件失败，请检查配置']);
        }
    }

    /**
     * @Get("/alipay", name="admin.test.alipay")
     */
    public function alipayAction()
    {
        $alipayTestService = new AlipayTestService();

        $this->db->begin();

        $order = $alipayTestService->createAlipayOrder();
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
     * @Get("/wxpay", name="admin.test.wxpay")
     */
    public function wxpayAction()
    {
        $wxpayTestService = new WxpayTestService();

        $this->db->begin();

        $order = $wxpayTestService->createWxpayOrder();
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
     * @Get("/alipay/status", name="admin.test.alipay_status")
     */
    public function alipayStatusAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $alipayTestService = new AlipayTestService();

        $status = $alipayTestService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Get("/wxpay/status", name="admin.test.wxpay_status")
     */
    public function wxpayStatusAction()
    {
        $sn = $this->request->getQuery('sn', 'string');

        $wxpayTestService = new WxpayTestService();

        $status = $wxpayTestService->status($sn);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/dingtalk/robot", name="admin.test.dingtalk_robot")
     */
    public function dingTalkRobotAction()
    {
        $noticeService = new DingTalkNoticeService();

        $result = $noticeService->test();

        if ($result) {
            return $this->jsonSuccess(['msg' => '发送消息成功，请到钉钉确认']);
        } else {
            return $this->jsonError(['msg' => '发送消息失败，请检查配置']);
        }
    }

}