<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Http\Api\Services\Setting as SettingService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/api/setting")
 */
class SettingController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/site", name="api.setting.site")
     */
    public function siteAction()
    {
        $service = new SettingService();

        $site = $service->getSiteSettings();

        return $this->jsonSuccess(['site' => $site]);
    }

    /**
     * @Get("/payment", name="api.setting.payment")
     */
    public function paymentAction()
    {
        $service = new SettingService();

        $payment = $service->getPaymentSettings();

        return $this->jsonSuccess(['payment' => $payment]);
    }

    /**
     * @Get("/register", name="api.setting.register")
     */
    public function registerAction()
    {
        $service = new SettingService();

        $register = $service->getRegisterSettings();

        return $this->jsonSuccess(['register' => $register]);
    }

}
