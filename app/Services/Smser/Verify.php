<?php

namespace App\Services\Smser;

use App\Services\Smser;
use App\Services\Verification;

class Verify extends Smser
{

    protected $templateCode = 'verify';

    public function handle($phone)
    {
        $verifyCode = new Verification();

        $minutes = 5;

        $code = $verifyCode->getSmsCode($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
