<?php

namespace App\Services\Smser;

use App\Services\Smser;

class Test extends Smser
{

    public function handle($phoneNumber)
    {
        $identity = new Verify();

        $result = $identity->handle($phoneNumber);

        return $result;
    }

}
