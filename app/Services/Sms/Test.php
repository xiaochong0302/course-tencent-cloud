<?php

namespace App\Services\Sms;

use App\Services\Smser;

class Test extends Smser
{

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $identity = new Verify();

        return $identity->handle($phone);
    }

}
