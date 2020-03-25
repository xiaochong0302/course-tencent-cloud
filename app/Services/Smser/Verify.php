<?php

namespace App\Services\Smser;

use App\Library\Util\Verification;
use App\Services\Smser;

class Verify extends Smser
{

    protected $templateCode = 'verify';

    public function handle($phone)
    {
        $minutes = 5;

        $code = Verification::code($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
