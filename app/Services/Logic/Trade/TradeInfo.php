<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Trade;

use App\Models\Trade as TradeModel;
use App\Models\User as UserModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\TradeTrait;
use App\Services\Logic\User\ShallowUserInfo;
use App\Services\Logic\UserTrait;

class TradeInfo extends LogicService
{

    use TradeTrait;
    use UserTrait;

    public function handle($sn)
    {
        $trade = $this->checkTradeBySn($sn);

        $user = $this->getLoginUser(true);

        return $this->handleTrade($trade, $user);
    }

    protected function handleTrade(TradeModel $trade, UserModel $user)
    {
        $statusHistory = $this->handleStatusHistory($trade->id);
        $order = $this->handleOrderInfo($trade->order_id);
        $owner = $this->handleOwnerInfo($trade->owner_id);
        $me = $this->handleMeInfo($trade, $user);

        return [
            'sn' => $trade->sn,
            'subject' => $trade->subject,
            'amount' => $trade->amount,
            'channel' => $trade->channel,
            'status' => $trade->status,
            'deleted' => $trade->deleted,
            'create_time' => $trade->create_time,
            'update_time' => $trade->update_time,
            'status_history' => $statusHistory,
            'order' => $order,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleOrderInfo($orderId)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        return [
            'id' => $order->id,
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
        ];
    }

    protected function handleOwnerInfo($userId)
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleStatusHistory($tradeId)
    {
        $tradeRepo = new TradeRepo();

        $records = $tradeRepo->findStatusHistory($tradeId);

        if ($records->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($records as $record) {
            $result[] = [
                'status' => $record->status,
                'create_time' => $record->create_time,
            ];
        }

        return $result;
    }

    protected function handleMeInfo(TradeModel $trade, UserModel $user)
    {
        $result = [
            'owned' => 0,
            'allow_cancel' => 0,
        ];

        if ($user->id == $trade->owner_id) {
            $result['owned'] = 1;
        }

        if ($trade->status == TradeModel::STATUS_PENDING) {
            $result['allow_cancel'] = 1;
        }

        return $result;
    }

}
