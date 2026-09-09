<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;
use App\Validators\Order as OrderValidator;

trait OrderTrait
{

    protected function checkOrderById(int $id): OrderModel
    {
        $validator = new OrderValidator();

        return $validator->checkById($id);
    }

    protected function checkOrderBySn(string $sn): OrderModel
    {
        $validator = new OrderValidator();

        return $validator->checkBySn($sn);
    }

    /**
     * 判断是否常规订单，排除了（虚拟充值|测试|验证）类型
     */
    protected function isNormalOrder(OrderModel $order): bool
    {
        $scopes = [
            KgProductModel::ITEM_COURSE,
            KgProductModel::ITEM_PACKAGE,
            KgProductModel::ITEM_VIP,
            KgProductModel::ITEM_EXAM_PAPER,
            KgProductModel::ITEM_ARTICLE,
        ];

        return in_array($order->item_type, $scopes);
    }

    /**
     * 判断订单能否分销抽成
     */
    protected function isAffiliateOrder(OrderModel $order): bool
    {
        $scopes = [
            KgProductModel::ITEM_COURSE,
            KgProductModel::ITEM_PACKAGE,
            KgProductModel::ITEM_VIP,
            KgProductModel::ITEM_EXAM_PAPER,
            KgProductModel::ITEM_ARTICLE,
        ];

        return in_array($order->item_type, $scopes);
    }

}
