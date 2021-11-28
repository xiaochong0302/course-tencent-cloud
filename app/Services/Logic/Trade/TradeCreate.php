<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;
use App\Validators\Trade as TradeValidator;

class TradeCreate extends LogicService
{

    use OrderTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $validator = new OrderValidator();

        $validator->checkIfAllowPay($order);

        $user = $this->getLoginUser();

        $validator = new TradeValidator();

        $channel = $validator->checkChannel($post['channel']);

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        return $trade;
    }

}
