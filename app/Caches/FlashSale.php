<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\FlashSale as FlashSaleRepo;

class FlashSale extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "flash_sale:{$id}";
    }

    public function getContent($id = null)
    {
        $saleRepo = new FlashSaleRepo();

        $sale = $saleRepo->findById($id);

        return $sale ?: null;
    }

}
