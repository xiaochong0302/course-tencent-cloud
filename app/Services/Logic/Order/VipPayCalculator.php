<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\User as UserModel;
use App\Models\Vip as VipModel;

class VipPayCalculator extends PayCalculator
{

    /**
     * @var VipModel
     */
    protected VipModel $vip;

    public function __construct(VipModel $vip)
    {
        $this->vip = $vip;
    }

    public function getTotalAmount(): float
    {
        return $this->vip->price;
    }

    public function handleNormalPay(UserModel $user): void
    {
        $this->totalAmount = $this->vip->price;
        $this->payAmount = $this->vip->price;
        $this->discountAmount = 0.00;
    }

}
