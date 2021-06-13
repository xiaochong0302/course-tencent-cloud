<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Refund as RefundValidator;

trait RefundTrait
{

    public function checkRefundById($id)
    {
        $validator = new RefundValidator();

        return $validator->checkRefund($id);
    }

    public function checkRefundBySn($sn)
    {
        $validator = new RefundValidator();

        return $validator->checkRefundBySn($sn);
    }

}
