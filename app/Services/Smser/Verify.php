<?php

namespace App\Services\Smser;

use App\Services\Smser;
use App\Services\Verification;

class Verify extends Smser
{

    protected $templateCode = 'verify';

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $verification = new Verification();

        $minutes = 5;

        $code = $verification->getSmsCode($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
