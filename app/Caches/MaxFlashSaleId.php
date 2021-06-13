<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\FlashSale as FlashSaleModel;

class MaxFlashSaleId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_flash_sale_id';
    }

    public function getContent($id = null)
    {
        $sale = FlashSaleModel::findFirst(['order' => 'id DESC']);

        return $sale->id ?? 0;
    }

}
