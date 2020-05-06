<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Services\Verification as VerifyService;

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

    public function checkSmsCode($phone, $code)
    {
        $service = new VerifyService();

        $result = $service->checkSmsCode($phone, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_code');
        }
    }

    public function checkEmailCode($email, $code)
    {
        $service = new VerifyService();

        $result = $service->checkEmailCode($email, $code);

        if (!$result) {
            throw new BadRequestException('verify.invalid_code');
        }
    }

    public function checkCode($key, $code)
    {
        $service = new VerifyService();

        $result = false;

        if (CommonValidator::email($key)) {
            $result = $service->checkEmailCode($key, $code);
        } elseif (CommonValidator::phone($key)) {
            $result = $service->checkSmsCode($key, $code);
        }

        if (!$result) {
            throw new BadRequestException('verify.invalid_code');
        }
    }

}
