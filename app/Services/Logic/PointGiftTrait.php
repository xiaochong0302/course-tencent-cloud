<?php

namespace App\Services\Logic;

use App\Validators\PointGift as PointGiftValidator;

trait PointGiftTrait
{

    public function checkPointGift($id)
    {
        $validator = new PointGiftValidator();

        return $validator->checkPointGift($id);
    }

    public function checkFlashSaleCache($id)
    {
        $validator = new PointGiftValidator();

        return $validator->checkPointGiftCache($id);
    }

}
