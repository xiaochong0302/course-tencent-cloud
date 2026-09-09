<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AlipayTest as AlipayTestService;
use App\Http\Admin\Services\WxpayTest as WxpayTestService;
use App\Services\Logic\Notice\External\Mail\Test as MailTestService;
use App\Services\Logic\Notice\External\Sms\Test as SmsTestService;
use App\Services\MyStorage as StorageService;
use App\Services\Vod as VodService;

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

        $result = $alipayTestService->handle();

        $this->view->pick('setting/pay_alipay_test');
        $this->view->setVar('sn', $result['sn']);
        $this->view->setVar('qrcode', $result['qrcode']);
    }

    /**
     * @Get("/wxpay", name="admin.test.wxpay")
     */
    public function wxpayAction()
    {
        $wxpayTestService = new WxpayTestService();

        $result = $wxpayTestService->handle();

        $this->view->pick('setting/pay_wxpay_test');
        $this->view->setVar('sn', $result['sn']);
        $this->view->setVar('qrcode', $result['qrcode']);
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

}
