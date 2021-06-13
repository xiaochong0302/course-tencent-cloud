<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\FlashSale;

use App\Services\Logic\Service as LogicService;

class UserOrderCache extends LogicService
{

    public function get($userId, $saleId)
    {
        $cache = $this->getCache();

        $keyName = $this->getKeyName($userId, $saleId);

        return $cache->get($keyName);
    }

    public function save($userId, $saleId)
    {
        $cache = $this->getCache();

        $keyName = $this->getKeyName($userId, $saleId);

        return $cache->save($keyName, 1, 2 * 3600);
    }

    public function delete($userId, $saleId)
    {
        $cache = $this->getCache();

        $keyName = $this->getKeyName($userId, $saleId);

        return $cache->delete($keyName);
    }

    protected function getKeyName($userId, $saleId)
    {
        return "flash_sale_user_order:{$userId}_{$saleId}";
    }

}
