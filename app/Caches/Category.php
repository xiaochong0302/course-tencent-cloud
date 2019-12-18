<?php

namespace App\Library\Cache;

use App\Exceptions\NotFound as ModelNotFoundException;
use App\Models\Category as CategoryModel;

class Category extends \Phalcon\Di\Injectable
{

    private $lifetime = 86400 * 30;

    public function getOrFail($id)
    {
        $result = $this->getById($id);

        if (!$result) {
            throw new ModelNotFoundException('category.not_found');
        }

        return $result;
    }

    public function get($id)
    {
        $cacheOptions = [
            'key' => $this->getKey($id),
            'lifetime' => $this->getLifetime(),
        ];

        $result = CategoryModel::query()
                ->where('id = :id:', ['id' => $id])
                ->cache($cacheOptions)
                ->execute()
                ->getFirst();

        return $result;
    }

    public function delete($id)
    {
        $key = $this->getKey($id);

        $this->modelsCache->delete($key);
    }

    public function getKey($id)
    {
        return "category:{$id}";
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }

}
