<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Order as OrderValidator;

trait OrderTrait
{

    public function checkOrderById($id)
    {
        $validator = new OrderValidator();

        return $validator->checkOrderById($id);
    }

    public function checkOrderBySn($sn)
    {
        $validator = new OrderValidator();

        return $validator->checkOrderBySn($sn);
    }

}
