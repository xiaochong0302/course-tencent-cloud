<?php

namespace App\Caches;

use App\Repos\FlashSale as FlashSaleRepo;

class FlashSale extends Cache
{

    protected $lifetime = 1 * 86400;

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
