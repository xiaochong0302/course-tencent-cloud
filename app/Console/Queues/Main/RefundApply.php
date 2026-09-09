<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Queues\Main;

use App\Models\KgPayment as KgPaymentModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class RefundApply extends Injectable
{

    use ServiceTrait;

    public function handle(TaskModel $task): void
    {
        echo '------ start refund apply task ------' . PHP_EOL;

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($task->item_id);

        if (!$refund) {
            throw new \RuntimeException("Refund Apply Task, Refund:{$task->item_id} Not Found");
        }

        $logger = $this->getLogger('refund');

        if ($refund->status != RefundModel::STATUS_APPROVED) {
            $logger->warning("Refund:{$refund->id} not approved, skip processing");
            return;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($refund->trade_id);

        $result = $this->applyRefund($trade, $refund);

        if (!$result) {
            throw new \RuntimeException('Refund Apply Task Failed');
        }

        echo '------ end refund apply task ------' . PHP_EOL;
    }

    /**
     * 发起平台退款申请
     */
    protected function applyRefund(TradeModel $trade, RefundModel $refund): bool
    {
        $result = false;

        if ($trade->channel == KgPaymentModel::CHANNEL_ALIPAY) {

            $service = new AlipayService();

            $result = $service->refund($refund);

        } elseif ($trade->channel == KgPaymentModel::CHANNEL_WXPAY) {

            $service = new WxpayService();

            $result = $service->refund($refund);
        }

        return $result;
    }

}
