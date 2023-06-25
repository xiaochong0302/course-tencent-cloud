<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Verify;

use App\Library\Captcha as AppCaptcha;
use App\Services\Logic\Service as LogicService;

class Captcha extends LogicService
{

    public function handle()
    {
        $captcha = new AppCaptcha();

        return $captcha->generate();
    }

}
