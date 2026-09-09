<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services\Traits;

use App\Repos\Refund as RefundRepo;

trait RefundSearchTrait
{

    protected function handleRefundSearchParams(array $params): array
    {
        /**
         * 兼容退款编号或退款序号查询
         */
        if (!empty($params['refund_id']) && strlen($params['refund_id']) > 10) {

            $refundRepo = new RefundRepo();

            $refund = $refundRepo->findBySn($params['refund_id']);

            $params['refund_id'] = $refund ? $refund->id : -1000;
        }

        return $params;
    }

}
