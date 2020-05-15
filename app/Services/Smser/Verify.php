<?php

namespace App\Services\Smser;

use App\Services\Smser as SmserService;
use App\Services\Verify as VerifyService;

class Verify extends SmserService
{

    protected $templateCode = 'verify';

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $verify = new VerifyService();

        $minutes = 5;

        $code = $verify->getSmsCode($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
