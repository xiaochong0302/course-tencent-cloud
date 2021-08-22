<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Verify\MailCode as MailCodeService;
use App\Services\Logic\Verify\SmsCode as SmsCodeService;
use App\Services\Logic\Verify\Ticket as TicketService;

/**
 * @RoutePrefix("/api/verify")
 */
class VerifyController extends Controller
{

    /**
     * @Post("/ticket", name="api.verify.ticket")
     */
    public function ticketAction()
    {
        $service = new TicketService();

        $ticket = $service->handle();

        return $this->jsonSuccess(['ticket' => $ticket]);
    }

    /**
     * @Post("/sms/code", name="api.verify.sms_code")
     */
    public function smsCodeAction()
    {
        $service = new SmsCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/mail/code", name="api.verify.mail_code")
     */
    public function mailCodeAction()
    {
        $service = new MailCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
