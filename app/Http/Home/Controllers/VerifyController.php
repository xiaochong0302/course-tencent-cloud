<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/verify")
 */
class VerifyController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/captcha", name="home.verify.captcha")
     */
    public function captchaAction()
    {
        $this->view->pick('verify/captcha');
    }

}
