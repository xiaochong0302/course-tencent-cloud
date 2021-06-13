<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Refund;

use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Refund;

class RefundConfirm extends LogicService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new Refund();

        return $service->preview($order);
    }

}
