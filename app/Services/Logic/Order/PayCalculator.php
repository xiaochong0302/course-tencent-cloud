<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\User as UserModel;

abstract class PayCalculator
{

    /**
     * @var float 商品总价
     */
    public float $totalAmount = 0.00 {
        get {
            return $this->totalAmount;
        }
    }

    /**
     * @var float 支付金额
     */
    public float $payAmount = 0.00 {
        get {
            return $this->payAmount;
        }
    }

    /**
     * @var float 优惠金额
     */
    public float $discountAmount = 0.00 {
        get {
            return $this->discountAmount;
        }
    }

    abstract public function handleNormalPay(UserModel $user): void;

}
