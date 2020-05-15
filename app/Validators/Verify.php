<?php

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

    public function checkCode($name, $code)
    {
        if (CommonValidator::email($name)) {
            $this->checkEmailCode($name, $code);
        } elseif (CommonValidator::phone($name)) {
            $this->checkSmsCode($name, $code);
        }
    }

    public function checkSmsCode($phone, $code)
    {
        $service = new VerifyService();

        $result = $service->checkSmsCode($phone, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_sms_code');
        }
    }

    public function checkEmailCode($email, $code)
    {
        $service = new VerifyService();

        $result = $service->checkEmailCode($email, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_email_code');
        }
    }

}
