<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Services\Verify as VerifyService;

class Verify extends Validator
{

    public function checkPhone($phone)
    {
        if (!CommonValidator::phone($phone)) {
            throw new BadRequestException('verify.invalid_phone');
        }

        return $phone;
    }

    public function checkEmail($email)
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException('verify.invalid_email');
        }

        return $email;
    }

    public function checkCode($identity, $code)
    {
        if (CommonValidator::email($identity)) {
            $this->checkMailCode($identity, $code);
        } elseif (CommonValidator::phone($identity)) {
            $this->checkSmsCode($identity, $code);
        } else {
            throw new BadRequestException('verify.invalid_code');
        }
    }

    public function checkSmsCode($phone, $code)
    {
        if (empty($code)) {
            throw new BadRequestException('verify.invalid_sms_code');
        }

        $service = new VerifyService();

        $result = $service->checkSmsCode($phone, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_sms_code');
        }
    }

    public function checkMailCode($email, $code)
    {
        if (empty($code)) {
            throw new BadRequestException('verify.invalid_mail_code');
        }

        $service = new VerifyService();

        $result = $service->checkMailCode($email, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_mail_code');
        }
    }

}
