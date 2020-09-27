<?php

namespace App\Services\Logic;

use App\Validators\Danmu as DanmuValidator;

trait DanmuTrait
{

    public function checkDanmu($id)
    {
        $validator = new DanmuValidator();

        return $validator->checkDanmu($id);
    }

}
