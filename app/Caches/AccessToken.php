<?php

namespace App\Caches;

class AccessToken extends Cache
{

    protected $lifetime = 2 * 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "access_token:{$id}";
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
