<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\FlashSale as FlashSaleValidator;

trait FlashSaleTrait
{

    public function checkFlashSale($id)
    {
        $validator = new FlashSaleValidator();

        return $validator->checkFlashSale($id);
    }

    public function checkFlashSaleCache($id)
    {
        $validator = new FlashSaleValidator();

        return $validator->checkFlashSaleCache($id);
    }

}
