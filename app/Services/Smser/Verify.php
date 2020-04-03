<?php

namespace App\Services\Smser;

use App\Services\Smser;
use App\Services\VerifyCode;

class Verify extends Smser
{

    protected $templateCode = 'verify';

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $verifyCode = new VerifyCode();

        $minutes = 5;

        $code = $verifyCode->getSmsCode($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
