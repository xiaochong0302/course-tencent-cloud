<?php

namespace App\Services\Logic;

use App\Validators\PointGift as PointGiftValidator;

trait PointGiftTrait
{

    public function checkGift($id)
    {
        $validator = new PointGiftValidator();

        return $validator->checkGift($id);
    }

}
