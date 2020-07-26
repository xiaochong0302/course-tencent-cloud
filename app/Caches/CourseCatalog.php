<?php

namespace App\Caches;

use App\Builders\CourseCatalog as CourseCatalogBuilder;

class CourseCatalog extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_catalog:{$id}";
    }

    public function getContent($id = null)
    {
        $builder = new CourseCatalogBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
