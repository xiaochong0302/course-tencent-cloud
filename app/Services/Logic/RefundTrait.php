<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Refund as RefundModel;
use App\Validators\Refund as RefundValidator;

trait RefundTrait
{

    protected function checkRefundById(int $id): RefundModel
    {
        $validator = new RefundValidator();

        return $validator->checkById($id);
    }

    protected function checkRefundBySn(string $sn): RefundModel
    {
        $validator = new RefundValidator();

        return $validator->checkBySn($sn);
    }

}
