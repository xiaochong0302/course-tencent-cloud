<?php

namespace App\Caches;

use App\Repos\Category as CategoryRepo;

class Category extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "category:{$id}";
    }

    public function getContent($id = null)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            return new \stdClass();
        }

        return $category;
    }

}
