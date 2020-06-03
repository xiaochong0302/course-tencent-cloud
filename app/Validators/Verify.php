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

    public function checkCode($identity, $code)
    {
        if (CommonValidator::email($identity)) {
            $this->checkEmailCode($identity, $code);
        } elseif (CommonValidator::phone($identity)) {
            $this->checkSmsCode($identity, $code);
        } else {
            throw new BadRequestException('verify.unsupported_identity');
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
