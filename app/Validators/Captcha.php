<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Captcha as ImageCaptcha;

class Captcha extends Validator
{

    public function checkCode($ticket, $rand)
    {
        $captcha = new ImageCaptcha();

        $result = $captcha->check($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('captcha.invalid_code');
        }
    }

}
