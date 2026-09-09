<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services\Traits;

use App\Repos\Trade as TradeRepo;

trait TradeSearchTrait
{

    protected function handleTradeSearchParams(array $params): array
    {
        /**
         * 兼容交易编号或交易序号查询
         */
        if (!empty($params['trade_id']) && strlen($params['trade_id']) > 10) {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findBySn($params['trade_id']);

            $params['trade_id'] = $trade ? $trade->id : -1000;
        }

        return $params;
    }

}
