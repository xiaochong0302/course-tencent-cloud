<?php

namespace App\Services\Logic\FlashSale;

use App\Services\Logic\Service;

class UserOrderCache extends Service
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
